<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\Support\CommentsController;
use App\Http\Controllers\Support\TicketController;


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
//     return view('welcome');
// });

Route::middleware('guest')->group(function () {
    Route::get('register', [RegistrationController::class, 'create']);
    Route::post('register', [RegistrationController::class, 'store']);

    Route::get('login', [AuthController::class, 'create'])->name('login.create');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});


// Route::middleware('auth')->group(function () {
Route::group(['middleware' => ['auth']], function () {
    Route::get('/', fn () => view('home'))->name('user.page');
    Route::get('dashboard', fn () => view('dashboard'))->name('admin.page');
    Route::get('home', fn () => view('home'))->name('user.page');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('companies', CompaniesController::class);

    Route::resource('branch', BranchController::class);

    Route::resource('department', DepartmentController::class);

    Route::resource('designation', DesignationController::class);

    Route::get('employee/bulk_add', [EmployeeController::class, 'bulk_add']);
    Route::resource('employee', EmployeeController::class);


    // Route::resource('roles', RoleController::class)->middleware('role:Admin');
    Route::resource('roles', RoleController::class);

    Route::resource('users', UserController::class);

    Route::post('api/fetch-department', [DropdownController::class, 'fetchDepartment']);
    Route::post('api/fetch-designation', [DropdownController::class, 'fetchDesignation']);
    Route::post('api/fetch-assigned', [DropdownController::class, 'fetchEmployeeDept']);
    Route::post('api/fetch-employee', [DropdownController::class, 'fetchEmployeeDept']);

    Route::resource('tickets', TicketsController::class);

    Route::resource('support/myticket', TicketController::class);
    Route::resource('support/comment', CommentsController::class);
});


Route::view('contact', 'contact');

Route::view('blankpage', 'blankpage');

// authorization
// Route::get('login', [LoginController::class, 'create'])->name('login');
// Route::post('login', [LoginController::class, 'store']);
