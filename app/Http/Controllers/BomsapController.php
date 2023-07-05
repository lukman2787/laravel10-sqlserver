<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Staudenmeir\EloquentRecursive\RecursiveTrait;
use DataTables;

use Staudenmeir\LaravelCte\Query\Builder;

class BomsapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        return view('productions.bill_of_material.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function fetch_bill_of_material(request $request)
    {
        if ($request->ajax()) {
            $query = DB::connection('sqlsrv')->table('ITT1')
                ->select([
                    DB::raw("CAST(2 AS INTEGER) AS depth"),
                    DB::raw("((CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '10' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '-') AS lineage"),
                    'ITT1.code',
                    'ITT1.father',
                    'ITT1.childnum',
                    'ITT1.Quantity',
                    'ITT1.Warehouse',
                    'ITT1.Currency',
                    'ITT1.Price'
                ])
                ->where('ITT1.father', $request->filter_item)
                ->unionAll(function ($unionQuery) {
                    $unionQuery->select([
                        DB::raw('Dr.depth + 1 as depth'),
                        DB::raw("(Dr.lineage + (CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '10' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '-') AS lineage"),
                        'ITT1.code',
                        'ITT1.father',
                        'ITT1.childnum',
                        'ITT1.Quantity',
                        'ITT1.Warehouse',
                        'ITT1.Currency',
                        'ITT1.Price'
                    ])
                        ->from('Dr')
                        ->join('ITT1', 'ITT1.father', '=', 'Dr.code')
                        ->whereRaw('ITT1.father <> ITT1.code');
                });

            $data = DB::connection('sqlsrv')->table('Dr')
                ->withRecursiveExpression('Dr(depth,lineage,code,Father,childnum,qty,Whse,Curr,Price)', $query)
                ->select([
                    'd.code as Item',
                    'i1.ItemName as ItemDescription',
                    'i1.InvntryUom as UoM',
                    'd.qty',
                    'd.Whse',
                    'd.Curr',
                    'd.Price',
                    'd.depth as Depth',
                    'i1.TreeType as BOMType',
                    DB::raw('(SELECT i2.ItmsGrpNam FROM OITB i2 WHERE i1.ItmsGrpCod = i2.ItmsGrpCod) as GroupName')
                ])
                ->from('Dr as d')
                ->join('OITM as i1', 'd.code', '=', 'i1.ItemCode')
                ->orderBy('lineage')->get();


            return datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $check_bom = '<label class="container-checkbox">
                                        <input type="checkbox" class="bomcheck" name="bom_checked[]" value="">
                                        <span class="checkmark"></span>
                                    </label>';
                    return $check_bom;
                })

                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function show_bom_treeview(Request $request)
    {

        $query = DB::connection('sqlsrv')->table('ITT1')
            ->select([
                DB::raw("CAST(2 AS INTEGER) AS depth"),
                DB::raw("((CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '10' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '-') AS lineage"),
                'ITT1.code',
                'ITT1.father',
                'ITT1.childnum',
                'ITT1.Quantity',
                'ITT1.Warehouse',
                'ITT1.Currency',
                'ITT1.Price',
                'ITT1.father AS parent'
            ])
            ->where('ITT1.father', $request->item_code)
            ->unionAll(function ($unionQuery) {
                $unionQuery->select([
                    DB::raw('Dr.depth + 1 AS depth'),
                    DB::raw("(Dr.lineage + (CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '10' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '-') AS lineage"),
                    'ITT1.code',
                    'ITT1.father',
                    'ITT1.childnum',
                    'ITT1.Quantity',
                    'ITT1.Warehouse',
                    'ITT1.Currency',
                    'ITT1.Price',
                    'Dr.code AS parent'
                ])
                    ->from('Dr')
                    ->join('ITT1', 'ITT1.father', '=', 'Dr.code')
                    ->whereRaw('ITT1.father <> ITT1.code');
            });

        $tree = DB::connection('sqlsrv')->table('Dr')
            ->withRecursiveExpression('Dr(depth,lineage,code,Father,childnum,qty,Whse,Curr,Price,parent)', $query)
            ->select([
                'd.code AS Item',
                'i1.ItemName AS ItemDescription',
                'i1.InvntryUom AS UoM',
                'd.qty',
                'd.Whse',
                'd.Curr',
                'd.Price',
                'd.depth AS Depth',
                'd.parent',
                'd.lineage',
                'i1.TreeType AS BOMType',
                DB::raw('(SELECT i2.ItmsGrpNam FROM OITB i2 WHERE i1.ItmsGrpCod = i2.ItmsGrpCod) AS GroupName')
            ])
            ->from('Dr AS d')
            ->join('OITM AS i1', 'd.code', '=', 'i1.ItemCode')
            ->orderBy('lineage')->get();

        foreach ($tree as $row) {
            $sub_data["id"] = $row->Item;
            $sub_data["name"] = $row->Item;
            $sub_data["text"] = $row->ItemDescription;
            $sub_data["parent_id"] = $row->parent;
            $data[] = $sub_data;
        }
        foreach ($data as $key => &$value) {
            $output[$value["id"]] = &$value;
        }
        foreach ($data as $key => &$value) {
            if ($value["parent_id"] && isset($output[$value["parent_id"]])) {
                $output[$value["parent_id"]]["nodes"][] = &$value;
            }
        }

        foreach ($data as $key => &$value) {
            if ($value["parent_id"] && isset($output[$value["parent_id"]])) {
                unset($data[$key]);
            }
        }

        return response()->json($data);
    }

    public function show_item_description(Request $request)
    {
        $data['item_description'] =  DB::connection('sqlsrv')->table('oitt as T0')
            ->join('oitm as T1', 'T1.ItemCode', '=', 'T0.code')
            ->select(
                'T0.Code',
                'T1.ItemName',
                'T1.InvntryUom',
                'T0.Qauntity',
                'T1.DfltWH',
                DB::raw('1 as depth'),
                DB::raw('(SELECT Distinct T2.Currency FROM ITM1 as T2 WHERE T2.PriceList = T0.PriceList And T2.ItemCode=T0.Code) as Curr'),
                DB::raw('(SELECT Distinct T2.Price FROM ITM1 as T2 WHERE T2.PriceList = T0.PriceList And T2.ItemCode=T0.Code) as Price'),
                'T0.TreeType as BomType',
                DB::raw('(SELECT T3.ItmsGrpNam FROM OITB as T3 WHERE T1.ItmsGrpCod =T3.ItmsGrpCod) as GroupName'),
                'T1.FrgnName',
                'T1.U_Dmsion',
                'T1.U_Material',
                'T1.U_Color',
                'T1.PicturName'
            )
            ->where('T0.Code', $request->item_code)->get();
        return response()->json($data);
    }
}
