<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    public function load_single_picture(Request $request)
    {
        $item_query = DB::connection('sqlsrv')->table('OITM AS T0')
            ->select('ItemCode', 'ItemName', 'PicturName')
            ->where('ItemCode', '=', $request->item_code)
            ->get();
        $output = array();
        foreach ($item_query as $row) {
            $output['ItemCode'] = $row->ItemCode;
            $output['ItemName'] = $row->ItemName;
            $output['PicturName'] = $row->PicturName;
            if ($row->PicturName != '') {
                // $source_path = 'X:/java/gbsap/pict/';
                // $image = file_get_contents($source_path . $row->PicturName);
                // $image_codes = base64_encode($image);
                // $output['ItemPicture'] = '<img src="data:image/jpg;charset=utf-8;base64,' . $image_codes . '" class="product-image" alt="Product Image" />';

                $manualPath = 'sample2.jpg';
                $imagePath = $row->PicturName;
                // $imageUrl = Storage::disks('images')->url($imagePath);
                // $output['ItemPicture'] = '<img src="' .  $imageUrl . '" class="product-image" alt="Product Image" />';
                // $output['ItemPicture'] = '<img src="' .  asset('storage/' . $manualPath) . '" class="product-image" alt="Product Image" />';
                $output['ItemPicture'] = '<img src="' .  asset('storage/' . 'no-image-item.png') . '" class="product-image" alt="Product Image" />';
            } else {
                // $output['ItemPicture'] = '<img src="storage/app/public/images/image-not-found.png" class="product-image" alt="Product Image"/>';
                $output['ItemPicture'] = '<img src="' .  asset('storage/' . 'no-image-item.png') . '" class="product-image" alt="Product Image" />';
            }
        }

        return response()->json($output);
    }
}
