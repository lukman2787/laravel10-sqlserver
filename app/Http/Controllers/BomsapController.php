<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

            // if (!empty($request->filter_item)) {
            //     $data = DB::connection('sqlsrv')->table('oitm as d')
            //         ->select('d.ItemCode', 'd.ItemName', 'd.FrgnName', 'd.ItmsGrpCod')
            //         ->where('ItemCode', $request->filter_item)
            //         ->get();
            // }

            if (!empty($request->filter_item)) {
                // $data = DB::connection('sqlsrv')::WITH Dr (depth,lineage,code,Father,childnum,qty,Whse,Curr,Price) AS 
                // (SELECT CAST( 2 AS INTEGER ) AS depth, ((CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '10' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '-') AS lineage, 
                // ITT1.code,ITT1.father,ITT1.childnum, ITT1.Quantity,ITT1.Warehouse,ITT1.Currency ,ITT1.Price from ITT1 Where ITT1.father ='J21067C-S-CN3A01-PJW' UNION ALL 
                // select Dr.depth + 1, (Dr.lineage + (CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '10' END) +CAST(ITT1.childnum AS VARCHAR(MAX)) + '-') as lineage, ITT1.code,ITT1.father,ITT1.childnum, ITT1.Quantity,ITT1.Warehouse,ITT1.Currency  ,ITT1.Price 
                // From Dr Inner Join ITT1 on ITT1.father=Dr.code where ITT1.father <> ITT1.code) 
                // SELECT d.code as Item,i1.ItemName as 'Item Description' ,i1.InvntryUom as UoM,d.qty as Quantity,d.Whse,d.Curr , d.Price, d.depth as Depth, i1.TreeType as 'BOM Type',(Select i2.ItmsGrpNam From OITB i2 where i1.ItmsGrpCod =i2.ItmsGrpCod) as GroupName  from Dr d ,OITM i1 where d.code=i1.ItemCode order by lineage;


                // $query_dr = DB::connection('sqlsrv')->table('table1')->select('id');

                // $result = DB::table('table2')
                //     ->withExpression('Dr', $query_dr)
                //     ->whereIn('table2.id', DB::table('main')->select('id'))
                //     ->get();

                // $data = DB::connection('sqlsrv')->table('Dr as d')
                //     ->with(['depth', 'lineage', 'code', 'Father', 'childnum', 'qty', 'Whse', 'Curr', 'Price'])
                //     ->get();
                // App\Request::where('id', 4)
                //     ->with('quotes', function ($query) {
                //         $query->where('status', '=', '3');
                //     })
                //     ->with('sourceTable', 'destinationTable')
                //     ->get();


                $query_union = DB::connection('sqlsrv')->table('itt1')
                    ->select(
                        'Dr.depth + 1',
                        DB::raw("CASE WHEN len(ITT1.childnum)=2 THEN 1 ELSE 10 END AS line1"),
                        DB::raw("CAST(ITT1.childnum as Varchar) as line2"),
                        // DB::raw("CAST(Dr.lineage + line1 + line2 + '-') as lineage"),
                        'ITT1.code',
                        'ITT1.father',
                        'ITT1.childnum',
                        'ITT1.Quantity',
                        'ITT1.Warehouse',
                        'ITT1.Currency',
                        'ITT1.Price'
                    )
                    ->where('ITT1.father', '<>', 'ITT1.Code');

                $query = DB::connection('sqlsrv')->table('itt1')
                    ->where('ITT1.father', '=', $request->filter_item)
                    ->select(
                        // DB::raw("CAST(2 AS Integer) as depth"),
                        DB::raw("CASE WHEN len(ITT1.childnum) = 2 THEN 1 ELSE 10 END AS line3"),
                        DB::raw("CAST(ITT1.childnum as Varchar) as line4"),
                        // DB::raw("CAST(line3 + line4 + '-') as lineage"),
                        'ITT1.code',
                        'ITT1.father',
                        'ITT1.childnum',
                        'ITT1.Quantity',
                        'ITT1.Warehouse',
                        'ITT1.Currency',
                        'ITT1.Price'
                    )
                    ->unionAll($query_union)
                    ->get();

                $data = $query;

                $query_akhir = DB::connection('sqlsrv')->table('Dr')
                    ->with(['depth', 'lineage', 'code', 'Father', 'childnum', 'qty', 'Whse', 'Curr', 'Price'])
                    ->crossJoin('OITM')
                    ->select('Dr.code as Item', 'OITM.ItemName as ItemDescription', 'OITM.InvntryUom as UoM', 'Dr.qty as Quantity', 'Dr.Whse', 'Dr.Curr', 'Dr.Price', 'Dr.depth as Depth', 'OITM.TreeType as BOM Type', ' as GroupName')
                    ->where('Dr.code', '=', DB::raw('OITM.ItemCode'))
                    ->orderBy('lineage', 'asc')
                    ->get();

                // $data = DB::connection('sqlsrv')->table('Dr')
                //     ->with(['depth', 'lineage', 'code', 'Father', 'childnum', 'qty', 'Whse', 'Curr', 'Price'])
                //     ->crossJoin('OITM')
                //     ->select('Dr.code as Item', 'OITM.ItemName as Item Description', 'OITM.InvntryUom as UoM', 'Dr.qty as Quantity', 'Dr.Whse', 'Dr.Curr', 'Dr.Price', 'Dr.depth as Depth', 'OITM.TreeType as BOM Type', ' as GroupName')
                //     ->where('Dr.code', '=', DB::raw('OITM.ItemCode'))
                //     ->withRecursiveExpression('Dr', $query)
                //     ->orderBy('lineage', 'asc')
                //     ->get();
            }

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
