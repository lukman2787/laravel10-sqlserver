<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestingController extends Controller
{
    public function bom_component(request $request)
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
            ->where('ITT1.father', 'J21067C-S-CN3A01-PJW')
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
                'd.father',
                'd.lineage',
                'd.childnum',
                'i1.TreeType AS BOMType',
                DB::raw("CASE WHEN i1.EvalSystem='A' THEN 'Moving Average' WHEN i1.EvalSystem='S' THEN 'Standard' WHEN i1.EvalSystem='F' THEN 'FIFO'
                END as evalMethod"),
                'i1.LastPurPrc',
                'i1.AvgPrice',
                DB::raw('(SELECT i2.ItmsGrpNam FROM OITB i2 WHERE i1.ItmsGrpCod = i2.ItmsGrpCod) AS GroupName'),
                // Calculate the Total Price (parent price) for each item with TreeType 'P'
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
                    END AS TotalPrice")
            ])
            ->from('Dr AS d')
            ->join('OITM AS i1', 'd.code', '=', 'i1.ItemCode')
            ->orderBy('lineage')
            ->get();


        // Group the data by the 'Depth' column
        $groupedByParent = $tree->where('parent', 'J21067C-S-CN3A01-PJW')->groupBy('parent');
        // $groupedByParent = $tree->groupBy('parent');
        // dd($groupedByParent);
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

        // $groupedByParent = $tree->groupBy('parent');


        $total_FG = 0;
        foreach ($result as $price_induk) {
            $total_FG = $total_FG + $price_induk['TotalValue'];
        }


        // dd($tree);
        echo '<pre>';
        // print_r($total_FG);
        print_r($result);
        echo '</pre>';

        // $query = DB::connection('sqlsrv')->table('ITT1')
        //     ->select([
        //         DB::raw("CAST(2 AS INTEGER) AS depth"),
        //         DB::raw("((CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '10' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '-') AS lineage"),
        //         'ITT1.code',
        //         'ITT1.father',
        //         'ITT1.childnum',
        //         'ITT1.Quantity',
        //         'ITT1.Warehouse',
        //         'ITT1.Currency',
        //         'ITT1.Price',
        //         'ITT1.father AS parent'
        //     ])
        //     ->where('ITT1.father', 'JG-SNHDS-LE-B-PJW')
        //     ->unionAll(function ($unionQuery) {
        //         $unionQuery->select([
        //             DB::raw('Dr.depth + 1 AS depth'),
        //             DB::raw("(Dr.lineage + (CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '10' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '-') AS lineage"),
        //             'ITT1.code',
        //             'ITT1.father',
        //             'ITT1.childnum',
        //             'ITT1.Quantity',
        //             'ITT1.Warehouse',
        //             'ITT1.Currency',
        //             'ITT1.Price',
        //             'Dr.code AS parent'
        //         ])
        //             ->from('Dr')
        //             ->join('ITT1', 'ITT1.father', '=', 'Dr.code')
        //             ->whereRaw('ITT1.father <> ITT1.code');
        //     });

        // $tree = DB::connection('sqlsrv')->table('Dr')
        //     ->withRecursiveExpression('Dr(depth,lineage,code,Father,childnum,qty,Whse,Curr,Price,parent)', $query)
        //     ->select([
        //         'd.code AS Item',
        //         'i1.ItemName AS ItemDescription',
        //         'i1.InvntryUom AS UoM',
        //         'd.qty',
        //         'd.Whse',
        //         'd.Curr',
        //         'd.Price',
        //         'd.depth AS Depth',
        //         'd.parent',
        //         'd.lineage',
        //         'i1.TreeType AS BOMType',
        //         DB::raw('(SELECT i2.ItmsGrpNam FROM OITB i2 WHERE i1.ItmsGrpCod = i2.ItmsGrpCod) AS GroupName')
        //     ])
        //     ->from('Dr AS d')
        //     ->join('OITM AS i1', 'd.code', '=', 'i1.ItemCode')
        //     ->orderBy('lineage')->get();

        // foreach ($tree as $row) {
        //     $sub_data["id"] = $row->Item;
        //     $sub_data["name"] = $row->Item;
        //     $sub_data["text"] = $row->ItemDescription;
        //     $sub_data["quantity"] = $row->qty;
        //     $sub_data["price"] = $row->Price;
        //     $sub_data["Tree_type"] = $row->BOMType;
        //     $sub_data["parent_id"] = $row->parent;
        //     $data[] = $sub_data;
        // }
        // foreach ($data as $key => &$value) {
        //     $output[$value["id"]] = &$value;
        // }
        // foreach ($data as $key => &$value) {
        //     if ($value["parent_id"] && isset($output[$value["parent_id"]])) {
        //         $output[$value["parent_id"]]["nodes"][] = &$value;
        //     }
        // }

        // foreach ($data as $key => &$value) {
        //     if ($value["parent_id"] && isset($output[$value["parent_id"]])) {
        //         unset($data[$key]);
        //     }
        // }

        // function calculateTotalPrice($data)
        // {
        //     $totalPrice = 0;
        //     foreach ($data as $item) {
        //         if ($item['Tree_type'] === 'P') {
        //             // If the item is of type 'P', calculate the total price of its children
        //             if (isset($item['nodes']) && is_array($item['nodes'])) {
        //                 $totalPrice += calculateTotalPrice($item['nodes']);
        //             }
        //         } else {
        //             // If the item is not of type 'P', calculate the price for this item
        //             $totalPrice += $item['quantity'] * $item['price'];
        //         }
        //     }
        //     return $totalPrice;
        // }

        // // Assuming your array is named $yourArray
        // $totalPriceForPItems = calculateTotalPrice($data);

        // // echo "Total price for items with TreeType 'P': " . $totalPriceForPItems;

        // // return response()->json($data);

        // echo '<pre>';
        // print_r($totalPriceForPItems);
        // echo '</pre>';
    }

    public function bom_price()
    {


        // Step 1: Create the Common Table Expression (CTE)
        $cte = DB::table('OITT AS T0')
            ->select(
                'T0.Code AS Father',
                'T0.Code',
                DB::raw('0 as Level'),
                'T2.TreeType',
                'T0.Qauntity',
                'T2.AvgPrice',
                'T2.EvalSystem'
            )
            ->join('OITM AS T2', 'T0.Code', '=', 'T2.ItemCode')
            ->join('OITW AS T3', function ($join) {
                $join->on('T2.ItemCode', '=', 'T3.ItemCode')
                    ->whereColumn('T0.ToWH', '=', 'T3.WhsCode');
            })
            ->where('T0.Code', 'J40430S-W3S419-PJW21');

        // Step 2: Build the main query with recursive part
        $query = DB::table('ITT1 AS T1')
            ->select(
                DB::raw('ISNULL(T1.Father, "") AS Father'),
                'T1.Code',
                DB::raw('Level + 1 as Level'),
                'T2.TreeType',
                'T1.Quantity',
                'T2.AvgPrice',
                'T2.EvalSystem'
            )
            ->join('OITM AS T2', 'T1.Code', '=', 'T2.ItemCode')
            ->join('OITW AS T3', function ($join) {
                $join->on('T2.ItemCode', '=', 'T3.ItemCode')
                    ->whereColumn('T1.Warehouse', '=', 'T3.WhsCode');
            })
            ->joinSub($cte, 'BOM', function ($join) {
                $join->on('T1.Father', '=', 'BOM.Code');
            });

        // Step 3: Perform the final SELECT queries
        // $result = $query
        //     ->unionAll($cte)
        //     ->select('*')
        //     ->into('#TMP')
        //     ->option('MAXRECURSION 99')
        //     ->get();
        $result = $query
            ->unionAll($cte)
            ->option('MAXRECURSION 99')
            ->get();

        $tmp2 = DB::table('#TMP AS T0')
            ->select(
                'T0.*',
                DB::raw('CASE T0.TreeType WHEN "N" THEN (SELECT DISTINCT X0.Quantity FROM #TMP X0 WHERE X0.Level = T0.Level - 1 AND X0.Code = T0.Father) * T0.Quantity * T0.Price ELSE 0 END AS Total')
            )
            ->into('#TMP2')
            ->get();

        $result = DB::table('#TMP2 AS T0')
            ->select(
                'T0.*',
                DB::raw('CASE T0.Level WHEN 0 THEN (SELECT SUM(X0.Total) FROM #TMP2 X0) ELSE 0 END AS "Item Cost FG"')
            )
            ->get();

        // Drop temporary tables
        DB::table('#TMP')->truncate();
        DB::table('#TMP2')->truncate();
        // DB::statement('DROP TABLE #TMP');
        // DB::statement('DROP TABLE #TMP2');
        print_r($result);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }
}
