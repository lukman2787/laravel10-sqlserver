<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FtpController extends Controller
{
    public function uploadToFtp(Request $request)
    {
        Storage::disk('ftp')->put('/', $request->file);
        return $request->file;
    }
}
