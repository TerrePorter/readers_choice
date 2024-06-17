<?php

use App\Models\Combos;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\CampaignController;
use Modules\Admin\Http\Controllers\CategoriesController;
use Modules\Admin\Http\Controllers\DataSendController;
use Modules\Admin\Http\Controllers\UsersController;
use Modules\Campaigns\Models\Campaign;
use Modules\Campaigns\Models\Categories;
use Modules\Campaigns\Models\CategoryTypes;
use Modules\SiteSettings\Services\CombosService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

Route::prefix('admin')->name('admin.')->middleware(['role:Admin|role:Moderator'])->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name("index");
    Route::get('users', [UsersController::class, 'index'])->name("users.index");
    Route::get('users/edit/{id}', [UsersController::class, 'edit'])->name("users.edit");

    Route::get('campaigns', [CampaignController::class, 'index'])->name("campaigns.index");
    Route::get('campaigns/edit_categories/{id}', [CampaignController::class, 'edit_categories'])->name("campaigns.edit_categories");

    Route::get('categories', [CategoriesController::class, 'index'])->name("categories.index");

    Route::get('/test', function () {

        $role = Role::firstOrCreate(['name' => 'Admin']);
        $permission = Permission::firstOrCreate(['name' => 'delete campaign', 'module' => 'Admin', 'guard_name' => 'web']);
        $permission->assignRole($role);

        dd('done');

    });

    Route::get('/admin/reported_profiles', function () {
        return view('reported_profiles');
    })->name('reported_profiles');

    Route::get('/admin/reported_messages', function () {
        return view('reported_messages');
    })->name('reported_messages');





    Route::post('/alpineDataSend', [DataSendController::class, 'handler'])->name("alpine_data_send");

});
