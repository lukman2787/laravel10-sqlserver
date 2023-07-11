<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


// class CostAnalysisDataExport implements FromCollection
// {
//     /**
//      * @return \Illuminate\Support\Collection
//      */
//     public function collection()
//     {
//         //
//     }
// }

class CostAnalysisDataExport implements FromView
{
    protected $data;
    protected $product;

    public function __construct($data, $product)
    {
        $this->data = $data;
        $this->product = $product;
    }

    public function view(): View
    {
        return view('productions.cost_analysis.costAnalysis_data', [
            'data' => $this->data,
            'induk' =>  $this->product
        ]);
    }
}
