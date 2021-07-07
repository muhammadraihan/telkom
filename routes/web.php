<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // check if user is auth then redirect to dashboard page
    if (Auth::check()) {
        return redirect()->route('backoffice.dashboard');
    }
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'backoffice', 'middleware' => ['auth']], function () {
    // backoffice route
    Route::get('/', 'DashboardController@index');
    Route::get('dashboard', 'DashboardController@dashboard')->name('backoffice.dashboard');
    Route::get('logs', 'ActivityController@index')->name('logs');

    // repair
    Route::get('repair/assign/history', 'RepairController@AssignHistory')->name('repair.assign-history');
    Route::get('repair/job/history', 'RepairController@RepairHistory')->name('repair.job-history');
    Route::get('repair/history/detail/{repair}', 'RepairController@detail')->name('repair.job-detail');
    Route::get('repair-job/detail/{repair_job}', 'RepairJobController@detail')->name('repair-job.detail');
    Route::get('repair-job/history', 'RepairJobController@history')->name('repair-job.history');

    // reference
    Route::get('getUnit', 'UnitController@GetUnitByWitel')->name('getUnit');
    Route::get('getModuleName', 'ModuleNameController@GetModuleNameByCategory')->name('getModuleName');
    Route::get('getModuleBrand', 'ModuleBrandController@GetModuleBrandByName')->name('getModuleBrand');
    Route::get('getModuleType', 'ModuleTypeController@GetModuleTypeByBrand')->name('getModuleType');

    // resource
    Route::resource('accessory', 'AccessoryController');
    Route::resource('brand', 'ModuleBrandController');
    Route::resource('category', 'ModuleCategoryController');
    Route::resource('gudang', 'GudangController');
    Route::resource('itemreplace', 'ItemReplaceController');
    Route::resource('material', 'MaterialController');
    Route::resource('name', 'ModuleNameController');
    Route::resource('permissions', 'PermissionController');
    Route::resource('repair', 'RepairController');
    Route::resource('roles', 'RoleController');
    Route::resource('stock', 'ModuleStockController');
    Route::resource('ticketing', 'TicketingController');
    Route::resource('repair-job', 'RepairJobController');
    Route::resource('type', 'ModuleTypeController');
    Route::resource('users', 'UserController');
    Route::resource('unit', 'UnitController');
    Route::resource('witel', 'WitelController');

    // user Profile
    Route::get('profile', 'UserController@profile')->name('profile');
    Route::patch('profile/{user}/update', 'UserController@ProfileUpdate')->name('profile.update');
    Route::patch('profile/{user}/password', 'UserController@ChangePassword')->name('profile.password');
});
