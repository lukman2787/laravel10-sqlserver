<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\CostAnalysisDataExport;
use App\Exports\BillofMaterialExport;
use Maatwebsite\Excel\Facades\Excel;
use Staudenmeir\LaravelCte\Eloquent\Builder;

class CostAnalysisController extends Controller
{
    public function index(request $request)
    {
        return view('productions.cost_analysis.index');
    }

    /** data to export excel */
    public function exportCostAnalysisData(Request $request)
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
                DB::raw('(SELECT Distinct T2.Price FROM ITM1 as T2 WHERE T2.PriceList = T0.PriceList And T2.ItemCode=T0.Code) as BomPrice'),
                'T1.LastPurPrc',
                'T1.AvgPrice AS Price',
                'T1.EvalSystem',
                'T0.TreeType as BomType',
                DB::raw('(SELECT T3.ItmsGrpNam FROM OITB as T3 WHERE T1.ItmsGrpCod =T3.ItmsGrpCod) as GroupName'),
            )
            ->where('T0.Code', $request->item_code)->first();

        $query = DB::connection('sqlsrv')->table('ITT1')
            ->select([
                DB::raw("CAST(2 AS INTEGER) AS depth"),
                DB::raw("((CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '1.' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '.') AS lineage"),
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
                    DB::raw("(Dr.lineage + (CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '1.' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '.') AS lineage"),
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
                'd.Father',
                'd.parent',
                'd.lineage',
                'd.childnum',
                'i1.TreeType AS BOMType',
                DB::raw("CASE 
                    WHEN i1.EvalSystem='A' THEN 'Moving Average'
                    WHEN i1.EvalSystem='S' THEN 'Standard'
                    WHEN i1.EvalSystem='F' THEN 'FIFO'
                END as evalMethod"),
                'i1.LastPurPrc',
                'i1.AvgPrice',
                DB::raw('(SELECT i2.ItmsGrpNam FROM OITB i2 WHERE i1.ItmsGrpCod = i2.ItmsGrpCod) AS GroupName'),
                DB::raw("CASE WHEN i1.TreeType IN ('P', 'N') THEN 
                        (SELECT 
                            CASE 
                                WHEN EXISTS (
                                    SELECT 1 FROM Dr AS child 
                                    WHERE child.lineage LIKE CONCAT(d.lineage, '%') AND child.lineage <> d.lineage
                                ) THEN d.qty * 
                                    (SELECT SUM(child.Price * child.qty) 
                                        FROM Dr AS child 
                                        WHERE child.lineage LIKE CONCAT(d.lineage, '%') AND child.lineage <> d.lineage
                                    )
                                ELSE d.qty * d.Price
                            END
                        ) 
                    END AS ActualCostTotal"),

            ])
            ->from('Dr AS d')
            ->join('OITM AS i1', 'd.code', '=', 'i1.ItemCode')
            ->orderBy('lineage')->get();

        /** perhitungan total actual cost */
        $groupedByParent = $tree->where('parent', $request->item_code)->groupBy('parent');

        // Create an array to store the results
        $result = [];

        // Iterate through each depth level
        foreach ($groupedByParent as $parent => $items) {
            // Calculate the total value for this depth level
            $totalValue = $items->sum(function ($item) {
                return $item->qty * $item->AvgPrice;
            });

            // Store the total value in the result array
            $result[] = [
                'Parent' => $parent,
                'TotalValue' => $totalValue,
            ];
        }

        $actual_cost_total = 0;
        foreach ($result as $price_induk) {
            $actual_cost_total = $actual_cost_total + $price_induk['TotalValue'];
        }

        // dd($tree);
        $file_name = 'costAnalysis ' . $request->item_code . '.xlsx';
        return Excel::download(new CostAnalysisDataExport($tree, $induk_tree, $actual_cost_total), $file_name);
    }

    public function exportBomData()
    {
        // Query data hirarki dari database menggunakan model Anda
        // $hierarchyData = Data::all();

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
            ->where('ITT1.father', 'J21067CRS1-CN3B507PJW')
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
        $hierarchyData = DB::connection('sqlsrv')->table('Dr')
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
                'd.Father',
                'd.parent',
                'd.lineage',
                'i1.TreeType AS BOMType',
                DB::raw('(SELECT i2.ItmsGrpNam FROM OITB i2 WHERE i1.ItmsGrpCod = i2.ItmsGrpCod) AS GroupName')
            ])
            ->from('Dr AS d')
            ->join('OITM AS i1', 'd.code', '=', 'i1.ItemCode')
            ->orderBy('lineage')->get();

        foreach ($hierarchyData as $row) {
            $sub_data["id"] = $row->Item;
            // $sub_data["Item"] = $row->Item;
            $sub_data["ItemDescription"] = $row->ItemDescription;
            $sub_data["parent"] = $row->parent;
            $sub_data["BOMType"] = $row->BOMType;
            $data[] = $sub_data;
        }

        foreach ($data as $key => &$value) {
            $output[$value["id"]] = &$value;
        }

        foreach ($data as $key => &$value) {
            if ($value["parent"] && isset($output[$value["parent"]])) {
                $output[$value["parent"]]["nodes"][] = &$value;
            }
        }

        foreach ($data as $key => &$value) {
            if ($value["parent"] && isset($output[$value["parent"]])) {
                unset($data[$key]);
            }
        }

        return Excel::download(new BillofMaterialExport($data), 'filename.xlsx');

        // Lakukan perulangan hirarki di dalam data sebelum proses ekspor
        // $formattedData = $this->processHierarchy($hierarchyData, 'J21067CRS1-CN3B507PJW');

        // Panggil metode untuk melakukan ekspor ke Excel
        // return Excel::download(new BillofMaterialExport($formattedData), 'hierarchyBom_data.xlsx');
    }

    private function processHierarchy($data, $parentId, $level = 0)
    {
        $formattedData = [];

        foreach ($data as $item) {
            if ($item->parent == $parentId) {
                // Lakukan operasi atau tindakan yang Anda inginkan pada setiap item di sini
                // Misalnya, tambahkan item ke dalam array yang akan diekspor ke Excel

                // Contoh: Menambahkan item ke dalam array dengan informasi level hierarki
                $emptyColumn = '-'; // Kolom kosong
                $numRepeats = 5; // Jumlah perulangan

                $repeatedColumn = str_repeat($emptyColumn, $level);
                $columnName = $this->getColumnName($level);

                $formattedData[] = [
                    'name' => $repeatedColumn . ' ' . $item->Item,
                    $columnName => $item->Item,
                    // 'other_column' => $item->other_column,

                    // Tambahkan kolom lain sesuai kebutuhan
                ];

                // Panggil rekursif untuk memproses child item
                $childItems = $this->processHierarchy($data, $item->Item, $level + 1);
                $formattedData = array_merge($formattedData, $childItems);
            }
        }

        return $formattedData;
    }

    private function getColumnName($lvl)
    {
        $columnName = '';
        $level = $lvl + 1;

        if ($level < 26) {
            $columnName = chr(65 + $level); // Kolom A-Z
        } else {
            $columnName = chr(65 + floor($level / 26) - 1) . chr(65 + $level % 26); // Kolom AA, AB, AC, dst.
        }

        return $columnName;
    }

    /** summary the cost Analysis by SO*/
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
                    $check_bom = '
                                <a href="javascript:void(0)" class="btn btn-link generate_bom" data-id="' . $row->ItemCode . '">' . $row->ItemCode . '</a>
                            ';
                    return $check_bom;
                })
                ->addColumn('DownloadAction', function ($row) {
                    $check_bom = '
                                <a href="javascript:void(0)" class="downloadFile" data-item="' . $row->ItemCode . '"><i class="fas fa-file-excel"></i></a>
                            ';
                    return $check_bom;
                })
                ->addColumn('qty_order', function ($row) {
                    $qty_order = number_format($row->Quantity, 0);
                    return $qty_order;
                })

                ->addColumn('qty_order', function ($row) {
                    $qty_order = number_format($row->Quantity, 0);
                    return $qty_order;
                })

                ->addColumn('resume_plan_cost', function ($row) {
                    $query_plan_cost = $this->getresumePlanCost($row->ItemCode);
                    $plan_cost = number_format($query_plan_cost->Price, 2);
                    return $plan_cost;
                })
                ->addColumn('resume_actual_cost', function ($row) {
                    $query_actual_cost = $this->getresumeActualCost($row->ItemCode);
                    $groupedByParent = $query_actual_cost->where('parent', $row->ItemCode)->groupBy('parent');

                    // Create an array to store the results
                    $result = [];

                    // Iterate through each depth level
                    foreach ($groupedByParent as $parent => $items) {
                        // Calculate the total value for this depth level
                        $totalValue = $items->sum(function ($item) {
                            return $item->qty * $item->AvgPrice;
                        });

                        // Store the total value in the result array
                        $result[] = [
                            'Parent' => $parent,
                            'TotalValue' => $totalValue,
                        ];
                    }

                    $actual_cost = 0;
                    foreach ($result as $price_induk) {
                        $actual_cost = $actual_cost + $price_induk['TotalValue'];
                    }
                    return number_format($actual_cost, 2);
                })

                ->addColumn('diff_cost', function ($row) {
                    $query_plan_cost = $this->getresumePlanCost($row->ItemCode);
                    $plan_cost = $query_plan_cost->Price;

                    $query_actual_cost = $this->getresumeActualCost($row->ItemCode);
                    $groupedByParent = $query_actual_cost->where('parent', $row->ItemCode)->groupBy('parent');

                    // Create an array to store the results
                    $result = [];

                    // Iterate through each depth level
                    foreach ($groupedByParent as $parent => $items) {
                        // Calculate the total value for this depth level
                        $totalValue = $items->sum(function ($item) {
                            return $item->qty * $item->AvgPrice;
                        });

                        // Store the total value in the result array
                        $result[] = [
                            'Parent' => $parent,
                            'TotalValue' => $totalValue,
                        ];
                    }

                    $actual_cost = 0;
                    foreach ($result as $price_induk) {
                        $actual_cost = $actual_cost + $price_induk['TotalValue'];
                    }
                    $diff_cost = $actual_cost - $plan_cost;
                    return number_format($diff_cost, 2);
                })

                ->rawColumns(['ItemAction', 'resume_plan_cost', 'resume_actual_cost', 'diff_cost', 'DownloadAction'])
                ->make(true);
        }
    }

    public function getresumePlanCost($item_fg)
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
                DB::raw('(SELECT Distinct T2.Price FROM ITM1 as T2 WHERE T2.PriceList = T0.PriceList And T2.ItemCode=T0.Code) as BomPrice'),
                'T1.LastPurPrc',
                'T1.AvgPrice AS Price',
                DB::raw("CASE WHEN T1.EvalSystem='A' THEN 'Moving Average' WHEN T1.EvalSystem='S' THEN 'Standard' WHEN T1.EvalSystem='F' THEN 'FIFO'
            END as evalMethod"),
                'T0.TreeType',
                DB::raw('(SELECT T3.ItmsGrpNam FROM OITB as T3 WHERE T1.ItmsGrpCod =T3.ItmsGrpCod) as GroupName'),
            )
            ->where('T0.Code', $item_fg)->first();
        return $induk_tree;
    }

    public function getresumeActualCost($item_fg)
    {
        $query = DB::connection('sqlsrv')->table('ITT1')
            ->select([
                DB::raw("CAST(2 AS INTEGER) AS depth"),
                DB::raw("((CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '1.' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '.') AS lineage"),
                'ITT1.code',
                'ITT1.father',
                'ITT1.childnum',
                'ITT1.Quantity',
                'ITT1.Warehouse',
                'ITT1.Currency',
                'ITT1.Price',
                'ITT1.father AS parent'
            ])
            ->where('ITT1.father', $item_fg)
            ->unionAll(function ($unionQuery) {
                $unionQuery->select([
                    DB::raw('Dr.depth + 1 AS depth'),
                    DB::raw("(Dr.lineage + (CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '1.' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '.') AS lineage"),
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
                'd.childnum',
                'i1.TreeType AS BOMType',
                DB::raw("CASE WHEN i1.EvalSystem='A' THEN 'Moving Average' WHEN i1.EvalSystem='S' THEN 'Standard' WHEN i1.EvalSystem='F' THEN 'FIFO'
                END as evalMethod"),
                'i1.LastPurPrc',
                'i1.AvgPrice',
                DB::raw('(SELECT i2.ItmsGrpNam FROM OITB i2 WHERE i1.ItmsGrpCod = i2.ItmsGrpCod) AS GroupName'),
                DB::raw("CASE WHEN i1.TreeType IN ('P', 'N') THEN 
                        (SELECT 
                            CASE 
                                WHEN EXISTS (
                                    SELECT 1 FROM Dr AS child 
                                    WHERE child.lineage LIKE CONCAT(d.lineage, '%') AND child.lineage <> d.lineage
                                ) THEN d.qty * 
                                    (SELECT SUM(child.Price * child.qty) 
                                        FROM Dr AS child 
                                        WHERE child.lineage LIKE CONCAT(d.lineage, '%') AND child.lineage <> d.lineage
                                    )
                                ELSE d.qty * d.Price
                            END
                        ) 
                    END AS ActualCostTotal")
            ])
            ->from('Dr AS d')
            ->join('OITM AS i1', 'd.code', '=', 'i1.ItemCode')
            ->orderBy('lineage')
            ->get();
        return $tree;
    }

    /** Generate Show BOM Detail */
    public function show_parent_bom(Request $request)
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
                DB::raw('(SELECT Distinct T2.Price FROM ITM1 as T2 WHERE T2.PriceList = T0.PriceList And T2.ItemCode=T0.Code) as BomPrice'),
                'T1.LastPurPrc',
                'T1.AvgPrice AS Price',
                DB::raw("CASE WHEN T1.EvalSystem='A' THEN 'Moving Average' WHEN T1.EvalSystem='S' THEN 'Standard' WHEN T1.EvalSystem='F' THEN 'FIFO'
            END as evalMethod"),
                'T0.TreeType',
                DB::raw('(SELECT T3.ItmsGrpNam FROM OITB as T3 WHERE T1.ItmsGrpCod =T3.ItmsGrpCod) as GroupName'),
            )
            ->where('T0.Code', $request->item_code)->first();

        $query = DB::connection('sqlsrv')->table('ITT1')
            ->select([
                DB::raw("CAST(2 AS INTEGER) AS depth"),
                DB::raw("((CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '1.' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '.') AS lineage"),
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
                    DB::raw("(Dr.lineage + (CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '1.' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '.') AS lineage"),
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
                'd.childnum',
                'i1.TreeType AS BOMType',
                DB::raw("CASE WHEN i1.EvalSystem='A' THEN 'Moving Average' WHEN i1.EvalSystem='S' THEN 'Standard' WHEN i1.EvalSystem='F' THEN 'FIFO'
                END as evalMethod"),
                'i1.LastPurPrc',
                'i1.AvgPrice',
                DB::raw("CASE WHEN i1.TreeType IN ('P', 'N') THEN 
                            (SELECT 
                                CASE 
                                    WHEN EXISTS (
                                        SELECT 1 FROM Dr AS child 
                                        WHERE child.lineage LIKE CONCAT(d.lineage, '%') AND child.lineage <> d.lineage
                                    ) THEN d.qty * d.Price
                                    ELSE d.qty * 
                                        (SELECT SUM(child.Price * child.qty) 
                                            FROM Dr AS child 
                                            WHERE child.lineage LIKE CONCAT(d.lineage, '%') AND child.lineage <> d.lineage
                                        )
                                END
                            ) 
                        END AS PlanCost"),

                DB::raw("(SELECT i2.ItmsGrpNam FROM OITB i2 WHERE i1.ItmsGrpCod = i2.ItmsGrpCod) AS GroupName"),
                DB::raw("CASE WHEN i1.TreeType IN ('P', 'N') THEN 
                        (SELECT 
                            CASE 
                                WHEN EXISTS (
                                    SELECT 1 FROM Dr AS child 
                                    WHERE child.lineage LIKE CONCAT(d.lineage, '%') AND child.lineage <> d.lineage
                                ) THEN d.qty * 
                                    (SELECT SUM(child.Price * child.qty) 
                                        FROM Dr AS child 
                                        WHERE child.lineage LIKE CONCAT(d.lineage, '%') AND child.lineage <> d.lineage
                                    )
                                ELSE d.qty * d.Price
                            END
                        ) 
                    END AS ActualCostTotal_one"),

                DB::raw("CASE 
                                WHEN i1.TreeType IN ('P', 'N') THEN 
                                    CASE 
                                        WHEN EXISTS (
                                            SELECT 1 FROM Dr AS child INNER JOIN OITM i3 ON child.Father = i3.ItemCode
                                            WHERE child.lineage LIKE CONCAT(d.lineage, '%') AND child.lineage <> d.lineage AND i3.TreeType = 'P'
                                        ) THEN d.qty * 
                                            (SELECT SUM(child.Price * child.qty) 
                                                FROM Dr AS child INNER JOIN OITM i4 ON child.Father = i4.ItemCode
                                                WHERE child.lineage LIKE CONCAT(d.lineage, '%') AND child.lineage <> d.lineage AND i4.TreeType = 'P'
                                            )
                                        ELSE d.qty * d.Price
                                    END
                                END AS ActualCostTotal")
            ])
            ->from('Dr AS d')
            ->join('OITM AS i1', 'd.code', '=', 'i1.ItemCode')
            ->orderBy('lineage')
            ->get();



        $FatherName = $induk_tree->ItemName . ' ( ' . $induk_tree->U_Color . ' )';
        $price_total = $induk_tree->Quantity * $induk_tree->Price;

        /** perhitungan total actual cost */
        $groupedByParent = $tree->where('parent', $request->item_code)->groupBy('parent');

        // Create an array to store the results
        $result = [];

        // Iterate through each depth level
        foreach ($groupedByParent as $parent => $items) {
            // Calculate the total value for this depth level
            $totalValue = $items->sum(function ($item) {
                return $item->qty * $item->AvgPrice;
            });

            // Store the total value in the result array
            $result[] = [
                'Parent' => $parent,
                'TotalValue' => $totalValue,
            ];
        }

        $actual_cost_total = 0;
        foreach ($result as $price_induk) {
            $actual_cost_total = $actual_cost_total + $price_induk['TotalValue'];
        }


        $output = '';

        $output .= '<div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <span id="">' . $FatherName . '</span>
                                    </h3>
                                </div>

                                <div class="card-body table-responsive p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-head-fixed table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Code Ass</th>
                                                    <th>Code Sub Ass</th>
                                                    <th>Code Sub Ass</th>
                                                    <th>Code Sub Ass</th>
                                                    <th>Code Sub Ass</th>
                                                    <th>Code Sub Ass</th>
                                                    <th>Code Item</th>
                                                    <th>Item Description</th>
                                                    <th>Valuation Method</th>
                                                    <th>Tree Type</th>
                                                    <th>Qty</th>
                                                    <th>UoM</th>
                                                    <th>Price</th>
                                                    <th>Plan Cost (BoM)</th>
                                                    <th>Actual Cost</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
        $output .= '
                                            <tr class="table-info">
                                                <td>' . $induk_tree->depth . '</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>' . $induk_tree->ItemCode . '</td>
                                                <td>' . $induk_tree->ItemName . ' (' . $induk_tree->U_Color . ')</td>
                                                <td>' . $induk_tree->evalMethod . '</td>
                                                <td>' . $induk_tree->TreeType . '</td>
                                                <td>' . $induk_tree->Quantity . '</td>
                                                <td>' . $induk_tree->UoM . '</td>
                                                <td> ' . number_format($induk_tree->Price, 2) . '</td>
                                                <td>' . $induk_tree->Curr . ' ' . number_format($price_total, 2) . '</td>
                                                <td>' . $induk_tree->Curr . ' ' . number_format($actual_cost_total, 2) . '</td>
                                            </tr>';

        foreach ($tree as $key => $item) {
            $output .= '
                                            <tr>
                                                <td></td>
                                                <td>' . ($item->Depth == 2 ? $item->lineage : '') . '</td>
                                                <td>' . ($item->Depth == 3 ? $item->lineage : '') . '</td>
                                                <td>' . ($item->Depth == 4 ? $item->lineage : '') . '</td>
                                                <td>' . ($item->Depth == 5 ? $item->lineage : '') . '</td>
                                                <td>' . ($item->Depth == 6 ? $item->lineage : '') . '</td>
                                                <td>' . $item->Item . '</td>
                                                <td>' . $item->ItemDescription . '</td>
                                                <td>' . $item->evalMethod . '</td>
                                                <td>' . $item->BOMType . '</td>
                                                <td>' . number_format($item->qty, 4) . '</td>
                                                <td>' . $item->UoM . '</td>
                                                <td>' . number_format($item->Price, 2) . '</td>
                                                <td>' . number_format($item->qty * $item->Price, 2) . '</td>
                                                <td>' . number_format($item->ActualCostTotal, 0) . '</td>
                                                
                                            </tr>';
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


    public function get_bom_price()
    {
        $query_one = DB::connection('sqlsrv')->table('dbo.OITT AS T0')
            ->select(
                DB::raw("T0.Code AS Father"),
                DB::raw("T0.Code AS Item"),
                "T2.ItemName",
                DB::raw("0 AS Level"),
                "T2.TreeType",
                DB::raw("T0.Qauntity AS Quantity"),
                "T2.AvgPrice AS Price",
                "T2.EvalSystem"
            )
            ->join('OITM AS T2', 'T0.Code', '=', 'T2.ItemCode')
            ->join('OITW AS T3', function ($join) {
                $join->on('T2.ItemCode', '=', 'T3.ItemCode')
                    ->whereColumn('T0.ToWH', 'T3.WhsCode');
            })
            ->where('T0.Code', 'J21067C-S-CN3B507PJW');

        $query_two = DB::connection('sqlsrv')->table('dbo.ITT1 AS T1')
            ->select(
                DB::raw("ISNULL(T1.Father, '') AS Father"),
                'T1.Code AS Item',
                'T2.ItemName',
                DB::raw('Level + 1 AS Level'),
                'T2.TreeType',
                'T1.Quantity AS Quantity',
                'T2.AvgPrice AS Price',
                'T2.EvalSystem'
            )
            ->join('OITM AS T2', 'T1.Code', '=', 'T2.ItemCode')
            ->join('OITW AS T3', function ($join) {
                $join->on('T2.ItemCode', '=', 'T3.ItemCode')
                    ->whereColumn('T1.Warehouse', 'T3.WhsCode');
            })
            ->join('BOM', 'T1.Father', '=', 'BOM.Code');

        $bindings = array_merge($query_one->getBindings(), $query_two->getBindings());

        $sql = "WITH BOM(Father,Code,ItemName,Level,TreeType,Quantity,Price,EvalSystem) AS 
        (
            SELECT * FROM (" . $query_one->toSql() . ") AS temp_table
            UNION ALL
            SELECT * FROM (" . $query_two->toSql() . ") AS temp_table
        )
        SELECT Dr.Father, Code, ItemName, Dr.Level, TreeType, Quantity, Dr.Price, EvalSystem
        FROM BOM AS Dr";

        $tree_two = DB::connection('sqlsrv')->select($sql, $bindings);
        return $tree_two;
    }
}
