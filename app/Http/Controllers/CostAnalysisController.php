<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CostAnalysisController extends Controller
{
    public function index(request $request)
    {
        $induk_tree = DB::connection('sqlsrv')->table('OITT AS T0')
            ->join('OITM AS T1', 'T1.ItemCode', '=', 'T0.code')
            ->select(
                'T0.Code as ItemCode',
                'T1.ItemName',
                'T1.InvntryUom as UoM',
                'T0.Qauntity as Quantity',
                'T1.DfltWH as Whse',

                DB::raw('1 as depth'),
                DB::raw('(SELECT Distinct T2.Currency FROM ITM1 as T2 WHERE T2.PriceList = T0.PriceList And T2.ItemCode=T0.Code) as Curr'),
                DB::raw('(SELECT Distinct T2.Price FROM ITM1 as T2 WHERE T2.PriceList = T0.PriceList And T2.ItemCode=T0.Code) as Price'),
                'T0.TreeType as BomType',
                DB::raw('(SELECT T3.ItmsGrpNam FROM OITB as T3 WHERE T1.ItmsGrpCod =T3.ItmsGrpCod) as GroupName'),
            )
            ->where('T0.Code', 'J21067CRS1-CN3B507PJW')->first();

        $FatherCode = $induk_tree->ItemCode;
        $dp1 = $induk_tree->depth . $induk_tree->ItemCode;

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
            ->where('ITT1.father', 'J21067CRS1-CN3B507PJW')
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

        $tree = DB::connection('sqlsrv')->table('Dr')
            ->withRecursiveExpression('Dr(depth,lineage,code,Father,childnum,qty,Whse,Curr,Price)', $query)
            ->select([
                'd.lineage',
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
            // ->where('d.depth', '=', 2)
            ->orderBy('lineage')->get();

        // $tree->where('Depth', '=', 2);

        foreach ($tree as $key => $row) {
            $sub_data["id"] = $dp1;
            $sub_data["id1"] = $row->Depth . $row->Item . $key + 1;
            $sub_data["lineage"] = $row->lineage;
            $sub_data["item"] = $row->Item;
            $sub_data["item_name"] = $row->ItemDescription;
            $sub_data["text"] = $row->Item . '-' . $row->ItemDescription;
            $sub_data["UoM"] = $row->UoM;
            $sub_data["depth"] = $row->Depth;
            $sub_data["parent_depth"] = $row->lineage;
            $data[] = $sub_data;

            $childDept = $row->Depth;
            $childItem = $row->Item;

            if ($childDept == 2) {
                $dp2 = "2" . $childItem . $key + 1;
                // $output[$dp2]["nodes"][] = &$data;
                // $sub_data["nodes"] = &$data;
                // foreach ($data as $key => &$value) {
                //     if ($value["depth"] && isset($output[$value["depth"]])) {
                //         $output[$value["depth"]]["nodes"][] = &$value;
                //     }
                // }
                // $nodX = $TreeView1->Nodes->Add($dp1, "tvwChild", $dp2, $childItem, 2, 3);
                // $nodX->Expanded = true;
            }
        }


        foreach ($data as $key => &$value) {
            $output[$value["id"]] = &$value;
        }
        foreach ($data as $key => &$value) {
            if ($value["lineage"] && isset($output[$value["lineage"]])) {
                $output[$value["lineage"]]["nodes"][] = &$value;
            }
        }
        foreach ($data as $key => &$value) {
            if ($value["depth"] && isset($output[$value["depth"]])) {
                unset($data[$key]);
            }
        }

        // dd($tree);
        // dd($data);
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';

        return view('productions.cost_analysis.index');
    }

    public function fetch_sales_order(request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection('sqlsrv')->table('ORDR as T0')
                ->select(
                    'T0.DocNum',
                    'T1.ItemCode',
                    'T1.Dscription',
                    'T1.Quantity',
                    DB::raw("LTRIM(RTRIM(REPLACE(REPLACE(REPLACE(T2.U_Dmsion, CHAR(9), ''), CHAR(10), ''), CHAR(13), ''))) as U_Dmsion"),
                    DB::raw("LTRIM(RTRIM(REPLACE(REPLACE(REPLACE(T2.U_Material, CHAR(9), ''), CHAR(10), ''), CHAR(13), ''))) as U_Material"),
                    DB::raw("LTRIM(RTRIM(REPLACE(REPLACE(REPLACE(T2.U_Color, CHAR(9), ''), CHAR(10), ''), CHAR(13), ''))) as U_Color"),
                    DB::raw("CONVERT(varchar, T0.DocDate, 103) as DocDate"),
                    DB::raw("CONVERT(varchar, T0.DocDueDate, 103) as DocDueDate"),
                    'T0.CardCode',
                    'T0.NumAtCard'
                )
                ->join('RDR1 AS T1', 'T0.DocEntry', '=', 'T1.DocEntry')
                ->join('OITM AS T2', 'T1.ItemCode', '=', 'T2.ItemCode')
                ->where('T0.DocNum', $request->filter_so)
                ->where('T0.CANCELED', '<>', 'Y')
                ->orderBy('T0.DocNum')
                ->orderBy('T0.CardCode')
                ->orderBy('T0.CardName')
                ->orderBy('T0.NumAtCard', 'asc')
                ->get();

            return datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('ItemAction', function ($row) {
                    $check_bom = '<a href="javascript:void(0)" class="btn btn-link generate_bom" data-id="' . $row->ItemCode . '">' . $row->ItemCode . '</a>';
                    return $check_bom;
                })
                ->addColumn('qty_order', function ($row) {
                    $qty_order = number_format($row->Quantity, 0);
                    return $qty_order;
                })

                ->rawColumns(['ItemAction'])
                ->make(true);
        }
    }

    public function get_parent_bom(Request $request)
    {
        $induk_tree = DB::connection('sqlsrv')->table('OITT AS T0')
            ->join('OITM AS T1', 'T1.ItemCode', '=', 'T0.code')
            ->select(
                'T0.Code as ItemCode',
                'T1.ItemName',
                'T1.InvntryUom as UoM',
                'T0.Qauntity as Quantity',
                'T1.DfltWH as Whse',
                'T1.U_Color',
                DB::raw('1 as depth'),
                DB::raw('(SELECT Distinct T2.Currency FROM ITM1 as T2 WHERE T2.PriceList = T0.PriceList And T2.ItemCode=T0.Code) as Curr'),
                DB::raw('(SELECT Distinct T2.Price FROM ITM1 as T2 WHERE T2.PriceList = T0.PriceList And T2.ItemCode=T0.Code) as Price'),
                'T0.TreeType as BomType',
                DB::raw('(SELECT T3.ItmsGrpNam FROM OITB as T3 WHERE T1.ItmsGrpCod =T3.ItmsGrpCod) as GroupName'),
            )
            ->where('T0.Code', $request->item_code)->first();

        $FatherCode = $induk_tree->ItemCode;
        $FatherName = $induk_tree->ItemName . ' ( ' . $induk_tree->U_Color . ' )';
        $dp1 = $induk_tree->depth . $induk_tree->ItemCode;

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
            ->where('ITT1.father', $request->item_code)
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

        $tree = DB::connection('sqlsrv')->table('Dr')
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
                'd.lineage',
                'i1.TreeType as BOMType',
                DB::raw('(SELECT i2.ItmsGrpNam FROM OITB i2 WHERE i1.ItmsGrpCod = i2.ItmsGrpCod) as GroupName')
            ])
            ->from('Dr as d')
            ->join('OITM as i1', 'd.code', '=', 'i1.ItemCode')
            ->orderBy('lineage')->get();

        $output = '';

        $output .= '<div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title"><span id="">' . $FatherName . '</span></h3>
                                </div>

                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th colspan="3">Kode Ass</th>
                                                    <th class="text-end">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
        foreach ($tree as $key => $row) {

            if ($row->Depth == 2) {
                $output .= '<tr  data-widget="expandable-table" aria-expanded="true">
                                <td>
                                    <button type="button" class="btn btn-primary p-0">
                                        <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                                    </button>
                                    ' . $row->lineage . $row->Item . ' ' . $row->ItemDescription . '
                                </td>
                                <td>' . number_format($row->qty, 2) . ' ' . $row->UoM . '</td>
                                <td>' . $row->Curr . ' ' . number_format($row->Price, 0) . '</td>
                                <td>' . $row->Curr . ' ' . number_format($row->qty * $row->Price, 0) . '</td>
                            </tr>';
            }
            if ($row->Depth == 3) {
                $output .= '
                                            <tr class="expandable-body">
                                                <td>
                                                    <div class="p-0">
                                                        <table class="table table-hover table-sm">
                                                            <tbody>
                                                                <tr data-widget="expandable-table" aria-expanded="false">
                                                                    <td>
                                                                        <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                                                                        ' . $row->lineage . $row->Item . ' ' . $row->ItemDescription . '
                                                                    </td>
                                                                    <td style="width: 10%">' . number_format($row->qty, 2) . ' ' . $row->UoM . '</td>
                                                                    <td style="width: 10%">' . $row->Curr . ' ' . number_format($row->Price, 0) . '</td>
                                                                    <td style="width: 10%">' . $row->Curr . ' ' . number_format($row->qty * $row->Price, 0) . '</td>
                                                                </tr>';


                // if ($row->Depth >= 4) {
                //     $output .= '<tr class="expandable-body">
                //                                                 <td>
                //                                                     <div class="p-0">
                //                                                         <table class="table table-hover">
                //                                                             <tbody>
                //                                                                 <tr>
                //                                                                     <td>' . $row->Depth . $row->lineage . $row->Item . '</td>
                //                                                                     <td>' . number_format($row->qty, 2) . '</td>
                //                                                                     <td>' . number_format($row->Price, 0) . '</td>                                                                                </tr>
                //                                                             </tbody>
                //                                                         </table>
                //                                                     </div>
                //                                                 </td>
                //                                             </tr>';
                // }
                $output .= '
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                ';
            }
        }

        $output .= '</tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';



        return response()->json($output);
    }
}
