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

            if (!empty($request->filter_item)) {
                $data = DB::connection('sqlsrv')->table('oitm as d')
                    ->select('d.ItemCode', 'd.ItemName', 'd.FrgnName', 'd.ItmsGrpCod')
                    ->where('ItemCode', $request->filter_item)
                    ->get();
            }
            // if (!empty($request->filter_item)) {
            //     // $data = DB::connection('sqlsrv')::WITH Dr (depth,lineage,code,Father,childnum,qty,Whse,Curr,Price) AS 
            //     // (SELECT CAST( 2 AS INTEGER ) AS depth, ((CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '10' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '-') AS lineage, 
            //     // ITT1.code,ITT1.father,ITT1.childnum, ITT1.Quantity,ITT1.Warehouse,ITT1.Currency ,ITT1.Price from ITT1 Where ITT1.father ='J21067C-S-CN3A01-PJW' UNION ALL 
            //     // select Dr.depth + 1, (Dr.lineage + (CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '10' END) +CAST(ITT1.childnum AS VARCHAR(MAX)) + '-') as lineage, ITT1.code,ITT1.father,ITT1.childnum, ITT1.Quantity,ITT1.Warehouse,ITT1.Currency  ,ITT1.Price 
            //     // From Dr Inner Join ITT1 on ITT1.father=Dr.code where ITT1.father <> ITT1.code) 
            //     // SELECT d.code as Item,i1.ItemName as 'Item Description' ,i1.InvntryUom as UoM,d.qty as Quantity,d.Whse,d.Curr , d.Price, d.depth as Depth, i1.TreeType as 'BOM Type',(Select i2.ItmsGrpNam From OITB i2 where i1.ItmsGrpCod =i2.ItmsGrpCod) as GroupName  from Dr d ,OITM i1 where d.code=i1.ItemCode order by lineage;


            //     // $query_dr = DB::connection('sqlsrv')->table('table1')->select('id');

            //     // $result = DB::table('table2')
            //     //     ->withExpression('Dr', $query_dr)
            //     //     ->whereIn('table2.id', DB::table('main')->select('id'))
            //     //     ->get();

            //     // $data = DB::connection('sqlsrv')->table('Dr as d')
            //     //     ->with(['depth', 'lineage', 'code', 'Father', 'childnum', 'qty', 'Whse', 'Curr', 'Price'])
            //     //     ->get();

            //     $query_akhir = DB::table('Dr')
            //         ->crossJoin('OITM')
            //         ->select('Dr.code as Item', 'OITM.ItemName as Item Description', 'OITM.InvntryUom as UoM', 'Dr.qty as Quantity', 'Dr.Whse', 'Dr.Curr', 'Dr.Price', 'Dr.depth as Depth', 'OITM.TreeType as BOM Type', ' as GroupName')
            //         ->where('Dr.code', '=', DB::raw('OITM.ItemCode'))
            //         ->orderBy('lineage', 'asc')
            //         ->get();

            //     $query = DB::table('users')
            //         ->whereNull('parent_id')
            //         ->unionAll(
            //             DB::table('users')
            //                 ->select('users.*')
            //                 ->join('tree', 'tree.id', '=', 'users.parent_id')
            //         );

            //     $data = DB::table('tree')
            //         ->withRecursiveExpression('tree', $query)
            //         ->get();
            // }

            return datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $check_employee = '<label class="container-checkbox">
                                        <input type="checkbox" class="coacheck" name="emp_checked[]" value="">
                                        <span class="checkmark"></span>
                                    </label>';
                    return $check_employee;
                })

                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function show_item_description(Request $request)
    {
        // $query_one = DB::connection('sqlsrv')->table('oitt as T0')
        //     ->distinct('')
        //     ->get();

        $item_description =  DB::connection('sqlsrv')->table('oitt as T0')
            ->join('oitm as T1', 'T1.ItemCode', '=', 'T0.code')
            ->select('T0.Code', 'T1.ItemName', 'T1.InvntryUom', 'T0.Qauntity', 'T1.DfltWH', 'T0.TreeType as BomType', 'T1.FrgnName', 'T1.U_Dmsion', 'T1.U_Material', 'T1.U_Color', 'T1.PicturName')
            ->where('T0.Code', $request->item_code)->get();
        return response()->json($item_description);
    }
}
