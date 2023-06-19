<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Staudenmeir\EloquentRecursive\RecursiveTrait;

use Staudenmeir\LaravelCte\Query\Builder;

class BomsapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        $item_father = 'J22619-TWRG373-PJW';
        $result = DB::connection('sqlsrv')->table(function ($query) {
            $query->withRecursiveExpression('Dr(depth,lineage,code,Father,childnum,qty,Whse,Curr,Price)', function ($recursiveQuery) {
                $item_father = 'J22619-TWRG373-PJW';
                $recursiveQuery->select([
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
                    ->from('ITT1')
                    ->where('ITT1.father', DB::raw('"' . $item_father . '"'))
                    ->unionAll(function ($unionQuery) {
                        $unionQuery->select([
                            DB::raw('Dr.depth'),
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
            })
                ->select([
                    'd.code as Item',
                    'i1.ItemName as ItemDescription',
                    'i1.InvntryUom as UoM',
                    'd.Quantity',
                    'd.Warehouse',
                    'd.Currency',
                    'd.Price',
                    'd.depth as Depth',
                    'i1.TreeType as BOMType',
                    DB::raw('(SELECT i2.ItmsGrpNam FROM OITB i2 WHERE i1.ItmsGrpCod = i2.ItmsGrpCod) as GroupName')
                ])
                ->from('Dr as d')
                ->join('OITM as i1', 'd.code', '=', 'i1.ItemCode')
                ->orderBy('lineage');
        });
        // $results = $result->get();
        // dd($results);
        // echo '<pre>';
        // print_r($tree);
        // echo '</pre>';

        // return view('productions.bill_of_material.index', ['results' => $results]);
        return view('productions.bill_of_material.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function fetch_bill_of_material(request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->filter_item)) {
                $results = DB::connection('sqlsrv')->table(function ($query) {
                    $query->withRecursiveExpression('Dr', function ($recursiveQuery) {
                        // $item_father = $request->filter_item;
                        $item_father = 'J22619-TWRG373-PJW';
                        $recursiveQuery->select([
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
                            ->from('ITT1')
                            // ->where('ITT1.father', DB::raw("'" . $item_father . "'"))
                            ->where('ITT1.father', DB::raw("' . $item_father . '"))
                            ->unionAll(function ($unionQuery) {
                                $unionQuery->select([
                                    DB::raw('Dr.depth'),
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
                    })
                        ->select([
                            'd.code as Item',
                            'i1.ItemName as ItemDescription',
                            'i1.InvntryUom as UoM',
                            'd.Quantity',
                            'd.Warehouse',
                            'd.Currency',
                            'd.Price',
                            'd.depth as Depth',
                            'i1.TreeType as BOMType',
                            DB::raw('(SELECT i2.ItmsGrpNam FROM OITB i2 WHERE i1.ItmsGrpCod = i2.ItmsGrpCod) as GroupName')
                        ])
                        ->from('Dr as d')
                        ->join('OITM as i1', 'd.code', '=', 'i1.ItemCode')
                        ->orderBy('lineage');
                });
            }
            $data = $results->get();


            return datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $check_bom = '<label class="container-checkbox">
                                        <input type="checkbox" class="coacheck" name="emp_checked[]" value="">
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
        $query =  DB::connection('sqlsrv')->table('itt1 as T0')
            ->join('oitm as T1', 'T1.ItemCode', '=', 'T0.code')
            ->select(
                'T0.Father',
                'T0.VisOrder',
                'T0.Code',
                'T1.ItemName',
                'T1.InvntryUom as UoM',
                'T0.quantity',
                'T0.Warehouse',
                'T0.Currency',
                'T0.Price',
                'T1.TreeType',
                DB::raw('(Select T2.ItmsGrpNam From OITB T2 where T1.ItmsGrpCod =T2.ItmsGrpCod) as GroupName')
            )
            ->where('T0.Father', $request->item_code)->orderBy('T0.VisOrder')->get();
        // dd($query);

        foreach ($query as $key => $row) {
            $sub_data["Father"] = $row->Father;
            $sub_data["Code"] = $row->Code;
            $sub_data["Text"] = $row->Code;
            $sub_data["Qty"] = $row->quantity;
            $sub_data["Price"] = $row->Price;
            $data[] = $sub_data;

            $query_parent =  DB::connection('sqlsrv')->table('itt1 as T0')
                ->join('oitm as T1', 'T1.ItemCode', '=', 'T0.code')
                ->select(
                    'T0.Father',
                    'T0.VisOrder',
                    'T0.Code',
                    'T1.ItemName',
                    'T1.InvntryUom as UoM',
                    'T0.quantity',
                    'T0.Warehouse',
                    'T0.Currency',
                    'T0.Price',
                    'T1.TreeType',
                    DB::raw('(Select T2.ItmsGrpNam From OITB T2 where T1.ItmsGrpCod =T2.ItmsGrpCod) as GroupName')
                )
                ->where('T0.Father', $row->Code)->orderBy('T0.VisOrder')->get();
        }

        foreach ($data as $key => &$value) {
            $output[$value["Father"]] = &$value;
        }

        foreach ($data as $key => &$value) {
            if ($value["Code"] && isset($output[$value["Code"]])) {
                $output[$value["Code"]]["nodes"][] = &$value;
            }
        }

        foreach ($data as $key => &$value) {
            if ($value["Code"] && isset($output[$value["Code"]])) {
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
