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

    // user Profile
    Route::get('profile', 'UserController@profile')->name('profile');
    Route::patch('profile/{user}/update', 'UserController@ProfileUpdate')->name('profile.update');
    Route::patch('profile/{user}/password', 'UserController@ChangePassword')->name('profile.password');

    // reference
    Route::get('getUnit', 'UnitController@GetUnitByWitel')->name('getUnit');
    Route::get('getModuleName', 'ModuleNameController@GetModuleNameByCategory')->name('getModuleName');
    Route::get('getModuleBrand', 'ModuleBrandController@GetModuleBrandByName')->name('getModuleBrand');
    Route::get('getModuleType', 'ModuleTypeController@GetModuleTypeByBrand')->name('getModuleType');

    // ticket
    Route::get('ticketing/history', 'TicketingController@history')->name('ticketing.history');

    // repair
    Route::prefix('repair')->group(function () {
        Route::get('assign/history', 'RepairController@AssignHistory')->name('repair.assign-history');
        Route::get('job/history', 'RepairController@RepairHistory')->name('repair.job-history');
        Route::get('history/detail/{repair}', 'RepairController@RepairDetail')->name('repair.job-detail');
        Route::get('repair-job/history', 'RepairJobController@history')->name('repair-job.history');
        Route::get('repair-job/history/detail/{repair_job}', 'RepairJobController@detail')->name('repair-job.detail');
    });

    // warehouse
    Route::prefix('warehouse')->group(function () {
        Route::get('history', 'WarehouseController@history')->name('warehouse.history');
        Route::get('history/detail/{warehouse}', 'WarehouseController@detail')->name('warehouse.detail');
    });

    Route::prefix('report')->group(function () {
        // report page
        Route::get('repair-module/tech', 'ReportController@RepairModuleTech')->name('report.repair-module-tech');
        Route::get('repair-module/vendor', 'ReportController@RepairModuleVendor')->name('report.repair-module-vendor');
        Route::get('replace-module', 'ReportController@ReplaceModule')->name('report.replace-module');
        Route::get('all-module', 'ReportController@ModuleHandle')->name('report.module-handle');
        Route::get('total-module/witel', 'ReportController@TotalModulePerWitel')->name('report.total-module-per-witel');
        Route::get('total-module/by-witel', 'ReportController@TotalModuleByWitel')->name('report.total-module-by-witel');
        Route::get('total-module-handle', 'ReportController@TotalModuleHandle')->name('report.total-module-handle');
        Route::get('total-module-percentage', 'ReportController@TotalModulePercentage')->name('report.total-module-percentage');
        Route::get('total-module-repair-comparison', 'ReportController@TotalModuleRepairComparison')->name('report.total-module-repair-comparison');
        Route::get('inventory/module', 'ReportController@ModuleInventory')->name('report.inventory-module');
        Route::get('inventory/material', 'ReportController@MaterialInventory')->name('report.inventory-material');

        // report export
        Route::post('repair-module/tech/download', 'ReportController@RepairModuleTechExport')->name('download.repair-module-tech');
        Route::post('repair-module/vendor/download', 'ReportController@RepairModuleVendorExport')->name('download.repair-module-vendor');
        Route::post('replace-module/download', 'ReportController@ReplaceModuleExport')->name('download.replace-module');
        Route::post('module-handle/download', 'ReportController@ModuleHandleExport')->name('download.module-handle');
        Route::post('total-module/witel/download', 'ReportController@TotalModulePerWitelExport')->name('download.total-module-per-witel');
        Route::post('total-module/by-witel/download', 'ReportController@TotalModuleByWitelExport')->name('download.total-module-by-witel');
        Route::post('total-module-handle/download', 'ReportController@TotalModuleHandleExport')->name('download.total-module-handle');
        Route::post('total-module-percentage/download', 'ReportController@TotalModulePercentageExport')->name('download.total-module-percentage');
        Route::post('total-module-repair-comparison/download', 'ReportController@TotalModuleRepairComparisonExport')->name('download.total-module-repair-comparison');
        Route::post('inventory/module/download', 'ReportController@ModuleInventoryExport')->name('download.inventory-module');
        Route::post('inventory/material/download', 'ReportController@MaterialInventoryExport')->name('download.inventory-material');
    });

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
