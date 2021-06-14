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
    Route::get('teknisi/history', 'TeknisiController@history')->name('teknisi.history');
    Route::resource('users', 'UserController');
    Route::resource('permissions', 'PermissionController');
    Route::resource('roles', 'RoleController');
    Route::resource('stock_item', 'Stock_itemController');
    Route::resource('kelengkapan', 'KelengkapanController');
    Route::resource('buffer_stock', 'Buffer_stockController');
    Route::resource('customer', 'CustomerController');
    Route::resource('customer_type', 'Customer_typeController');
    Route::resource('ticketing', 'TicketingController');
    Route::resource('teknisi', 'TeknisiController');
    Route::resource('gudang', 'GudangController');
    Route::resource('itemreplace', 'Item_replaceController');
    Route::get('stockdetail', 'Item_replaceController@detailStock')->name('get.detailStock');
    Route::post('customer-save', 'TicketingController@CustomerStore')->name('post.customer');
    // user Profile
    Route::get('profile', 'UserController@profile')->name('profile');
    Route::patch('profile/{user}/update', 'UserController@ProfileUpdate')->name('profile.update');
    Route::patch('profile/{user}/password', 'UserController@ChangePassword')->name('profile.password');
});
