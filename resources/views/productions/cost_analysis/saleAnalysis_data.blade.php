<!DOCTYPE html>
<html lang="en">
<table>
    <thead>
        <tr>
            <th colspan="7" class="text-center">Product Sales Properties</th>
            <th colspan="3" class="text-center">Cost Analysis</th>
            <th colspan="2" class="text-center">GP Analysis</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Item No</th>
            <th>Item Description</th>
            <th>Qty Order</th>
            <th>Dimension</th>
            <th>Material</th>
            <th>Color</th>
            <th>Plan Cost (BOM)</th>
            <th>Actual Cost</th>
            <th>Cost Diff</th>
            <th class="text-center">Plan GP</th>
            <th class="text-center">Actual GP</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <?php
            /** Get Plan Cost*/
            $query_plan_cost = DB::connection('sqlsrv')
                ->table('OITT AS T0')
                ->join('OITM AS T1', 'T1.ItemCode', '=', 'T0.code')
                ->select('T0.Code as ItemCode', 'T1.ItemName', 'T1.InvntryUom as UoM', 'T0.Qauntity as Quantity', 'T1.DfltWH as Whse', 'T1.U_Color', DB::raw('(SELECT Distinct T2.Currency FROM ITM1 as T2 WHERE T2.PriceList = T0.PriceList And T2.ItemCode=T0.Code) as Curr'), DB::raw('(SELECT Distinct T2.Price FROM ITM1 as T2 WHERE T2.PriceList = T0.PriceList And T2.ItemCode=T0.Code) as BomPrice'), 'T1.LastPurPrc', 'T1.AvgPrice AS Price')
                ->where('T0.Code', $item->ItemCode)
                ->first();
            $plan_cost = $query_plan_cost->Price;
            
            /** Get Actual Cost*/
            $query = DB::connection('sqlsrv')
                ->table('ITT1')
                ->select([DB::raw('CAST(2 AS INTEGER) AS depth'), DB::raw("((CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '1.' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '.') AS lineage"), 'ITT1.code', 'ITT1.father', 'ITT1.childnum', 'ITT1.Quantity', 'ITT1.Warehouse', 'ITT1.Currency', 'ITT1.Price', 'ITT1.father AS parent'])
                ->where('ITT1.father', $item->ItemCode)
                ->unionAll(function ($unionQuery) {
                    $unionQuery
                        ->select([DB::raw('Dr.depth + 1 AS depth'), DB::raw("(Dr.lineage + (CASE WHEN len(ITT1.childnum)=2 THEN '1' ELSE '1.' END) + CAST(ITT1.childnum AS VARCHAR(MAX)) + '.') AS lineage"), 'ITT1.code', 'ITT1.father', 'ITT1.childnum', 'ITT1.Quantity', 'ITT1.Warehouse', 'ITT1.Currency', 'ITT1.Price', 'Dr.code AS parent'])
                        ->from('Dr')
                        ->join('ITT1', 'ITT1.father', '=', 'Dr.code')
                        ->whereRaw('ITT1.father <> ITT1.code');
                });
            
            $query_actual_cost = DB::connection('sqlsrv')
                ->table('Dr')
                ->withRecursiveExpression('Dr(depth,lineage,code,Father,childnum,qty,Whse,Curr,Price,parent)', $query)
                ->select(['d.code AS Item', 'i1.ItemName AS ItemDescription', 'i1.InvntryUom AS UoM', 'd.qty', 'd.Whse', 'd.Curr', 'd.Price', 'd.depth AS Depth', 'd.parent', 'd.lineage', 'd.childnum', 'i1.TreeType AS BOMType', 'i1.LastPurPrc', 'i1.AvgPrice'])
                ->from('Dr AS d')
                ->join('OITM AS i1', 'd.code', '=', 'i1.ItemCode')
                ->orderBy('lineage')
                ->get();
            $groupedByParent = $query_actual_cost->where('parent', $item->ItemCode)->groupBy('parent');
            
            // Create an array to store the results
            $result = [];
            
            // Iterate through each depth level
            foreach ($groupedByParent as $parent => $childs) {
                // Calculate the total value for this depth level
                $totalValue = $childs->sum(function ($child) {
                    return $child->qty * $child->AvgPrice;
                });
            
                // Store the total value in the result array
                $result[] = [
                    'TotalValue' => $totalValue,
                ];
            }
            
            $actual_cost = 0;
            foreach ($result as $price_induk) {
                $actual_cost = $actual_cost + $price_induk['TotalValue'];
            }
            $diff_cost = $actual_cost - $plan_cost;
            
            $value_gp = $item->PriceRate - $plan_cost;
            $plan_gp = ($value_gp / $item->PriceRate) * 100;
            
            $value_gp = $item->PriceRate - $actual_cost;
            $actual_gp = ($value_gp / $item->PriceRate) * 100;
            
            ?>
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $item->ItemCode }}</td>
                <td>{{ $item->Dscription }}</td>
                <td>{{ $item->Quantity }}</td>
                <td>{{ $item->U_Dmsion }}</td>
                <td>{{ $item->U_Material }}</td>
                <td>{{ $item->U_Color }}</td>
                <td>{{ $plan_cost }}</td>
                <td>{{ $actual_cost }}</td>
                <td>{{ $diff_cost }}</td>
                <td>{{ number_format($plan_gp, 2) }}%</td>
                <td>{{ number_format($actual_gp, 2) }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>

</html>
