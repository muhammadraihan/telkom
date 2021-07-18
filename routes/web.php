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
    Route::get('repair/history/detail/{repair}', 'RepairController@RepairDetail')->name('repair.job-detail');
    Route::get('repair-job/history', 'RepairJobController@history')->name('repair-job.history');
    Route::get('repair-job/history/detail/{repair_job}', 'RepairJobController@detail')->name('repair-job.detail');

    // reference
    Route::get('getUnit', 'UnitController@GetUnitByWitel')->name('getUnit');
    Route::get('getModuleName', 'ModuleNameController@GetModuleNameByCategory')->name('getModuleName');
    Route::get('getModuleBrand', 'ModuleBrandController@GetModuleBrandByName')->name('getModuleBrand');
    Route::get('getModuleType', 'ModuleTypeController@GetModuleTypeByBrand')->name('getModuleType');

    // warehouse
    Route::get('warehouse/history', 'WarehouseController@history')->name('warehouse.history');
    Route::get('warehouse/history/detail/{warehouse}', 'WarehouseController@detail')->name('warehouse.detail');

    // user Profile
    Route::get('profile', 'UserController@profile')->name('profile');
    Route::patch('profile/{user}/update', 'UserController@ProfileUpdate')->name('profile.update');
    Route::patch('profile/{user}/password', 'UserController@ChangePassword')->name('profile.password');

    // ticket
    Route::get('ticketing/history', 'TicketingController@history')->name('ticketing.history');

    // report
    Route::get('report/repair-module/tech', 'ReportController@RepairModuleTech')->name('report.repair-module-tech');
    Route::get('report/repair-module/vendor', 'ReportController@RepairModuleVendor')->name('report.repair-module-vendor');
    Route::get('report/replace-module', 'ReportController@ReplaceModule')->name('report.replace-module');
    Route::get('report/all-module', 'ReportController@ModuleHandle')->name('report.module-handle');
    Route::get('report/total-module/witel', 'ReportController@TotalModulePerWitel')->name('report.total-module-per-witel');
    Route::get('report/total-module/by-witel', 'ReportController@TotalModuleByWitel')->name('report.total-module-by-witel');
    Route::get('report/total-module-handle', 'ReportController@TotalModuleHandle')->name('report.total-module-handle');
    Route::get('report/total-module-percentage', 'ReportController@TotalModulePercentage')->name('report.total-module-percentage');
    Route::get('report/total-module-repair-comparison', 'ReportController@TotalModuleRepairComparison')->name('report.total-module-repair-comparison');
    Route::get('report/inventory/module', 'ReportController@ModuleInventory')->name('report.inventory-module');
    Route::get('report/inventory/material', 'ReportController@MaterialInventory')->name('report.inventory-material');

    // report export
    Route::post('report/repair-module/tech/download', 'ReportController@RepairModuleTechExport')->name('download.repair-module-tech');
    Route::post('report/repair-module/vendor/download', 'ReportController@RepairModuleVendorExport')->name('download.repair-module-vendor');
    Route::post('report/replace-module/download', 'ReportController@ReplaceModuleExport')->name('download.replace-module');
    Route::post('report/module-handle/download', 'ReportController@ModuleHandleExport')->name('download.module-handle');
    Route::post('report/total-module/witel/download', 'ReportController@TotalModulePerWitelExport')->name('download.total-module-per-witel');
    Route::post('report/total-module/by-witel/download', 'ReportController@TotalModuleByWitelExport')->name('download.total-module-by-witel');
    Route::post('report/total-module-handle/download', 'ReportController@TotalModuleHandleExport')->name('download.total-module-handle');
    Route::post('report/total-module-percentage/download', 'ReportController@TotalModulePercentageExport')->name('download.total-module-percentage');
    Route::post('report/total-module-repair-comparison/download', 'ReportController@TotalModuleRepairComparisonExport')->name('download.total-module-repair-comparison');
    Route::post('report/inventory/module/download', 'ReportController@ModuleInventoryExport')->name('download.inventory-module');
    Route::post('report/inventory/material/download', 'ReportController@MaterialInventoryExport')->name('download.inventory-material');

    // resource
    Route::resource('accessory', 'AccessoryController');
    Route::resource('brand', 'ModuleBrandController');
    Route::resource('category', 'ModuleCategoryController');
    Route::resource('material', 'MaterialController');
    Route::resource('name', 'ModuleNameController');
    Route::resource('permissions', 'PermissionController');
    Route::resource('repair', 'RepairController');
    Route::resource('repair-job', 'RepairJobController');
    Route::resource('roles', 'RoleController');
    Route::resource('stock', 'ModuleStockController');
    Route::resource('ticketing', 'TicketingController');
    Route::resource('type', 'ModuleTypeController');
    Route::resource('users', 'UserController');
    Route::resource('unit', 'UnitController');
    Route::resource('warehouse', 'WarehouseController');
    Route::resource('witel', 'WitelController');
});
