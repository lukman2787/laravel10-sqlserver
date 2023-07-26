<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class CostAnalysisDataExport implements FromView
{
    protected $data;
    protected $product;
    protected $actual_cost_total;

    public function __construct($data, $product, $actual_cost_total)
    {
        $this->data = $data;
        $this->product = $product;
        $this->actual_cost_total = $actual_cost_total;
    }

    public function view(): View
    {
        return view('productions.cost_analysis.costAnalysis_data', [
            'data' => $this->data,
            'induk' =>  $this->product,
            'item_cost_total' => $this->actual_cost_total,
        ]);
    }
}
