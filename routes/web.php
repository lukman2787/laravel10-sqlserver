<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BomsapController;
use App\Http\Controllers\CostAnalysisController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     $contacts = DB::connection('sqlsrv')->table('ocrd')->get();
//     return view('welcome');
// });

Route::middleware('guest')->group(function () {
    Route::get('register', [RegistrationController::class, 'create']);
    Route::post('register', [RegistrationController::class, 'store']);

    Route::get('login', [AuthController::class, 'create'])->name('login.create');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', fn () => view('dashboard'))->name('admin.page');
    Route::get('dashboard', fn () => view('dashboard'))->name('admin.page');
    Route::get('home', fn () => view('home'))->name('user.page');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('production/bill_of_material',  [BomsapController::class, 'index'])->name('bom-sap.index');
    Route::post('fetch_bom_treeview', [BomsapController::class, 'show_bom_treeview'])->name('bom-treeview.show');
    Route::get('fetch_bill_of_material', [BomsapController::class, 'fetch_bill_of_material'])->name('get_bom');
    Route::post('bom/item_description', [BomsapController::class, 'show_item_description'])->name('bom-item.show');

    Route::post('production/analysis_tree',  [CostAnalysisController::class, 'get_parent_bom'])->name('cost-analysis.parent');
    Route::get('production/cost-analysis',  [CostAnalysisController::class, 'index'])->name('cost-analysis.index');
    Route::get('fetch_sales_order', [CostAnalysisController::class, 'fetch_sales_order'])->name('sales_order.fetch');
});
