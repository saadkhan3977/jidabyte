<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

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

Route::get('/', function () {
    // dd('as');
    return view('welcome');
});

Route::get('/export-database', function () {
    function backupDatabaseAllTables($dbhost,$dbusername,$dbpassword,$dbname,$tables = '*'){
        $db = new mysqli($dbhost, $dbusername, $dbpassword, $dbname); 
    
        if($tables == '*') { 
            $tables = array();
            $result = $db->query("SHOW TABLES");
            while($row = $result->fetch_row()) { 
                $tables[] = $row[0];
            }
        } else { 
            $tables = is_array($tables)?$tables:explode(',',$tables);
        }
    
        $return = '';
    
        foreach($tables as $table){
            $result = $db->query("SELECT * FROM $table");
            $numColumns = $result->field_count;
    
            /* $return .= "DROP TABLE $table;"; */
            $result2 = $db->query("SHOW CREATE TABLE $table");
            $row2 = $result2->fetch_row();
    
            $return .= "\n\n".$row2[1].";\n\n";
    
            for($i = 0; $i < $numColumns; $i++) { 
                while($row = $result->fetch_row()) { 
                    $return .= "INSERT INTO $table VALUES(";
                    for($j=0; $j < $numColumns; $j++) { 
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = $row[$j];
                        if (isset($row[$j])) { 
                            $return .= '"'.$row[$j].'"' ;
                        } else { 
                            $return .= '""';
                        }
                        if ($j < ($numColumns-1)) {
                            $return.= ',';
                        }
                    }
                    $return .= ");\n";
                }
            }
    
            $return .= "\n\n\n";
        }
    
        $handle = fopen("backup-" . Carbon::now()->format('Y-m-d h-i-s').'.sql','w+');
        fwrite($handle,$return);
        fclose($handle);
        echo "Database Export Successfully!";
    }

    return backupDatabaseAllTables(env('DB_HOST'),env('DB_USERNAME'),env('DB_PASSWORD'),env('DB_DATABASE'));
});

Auth::routes(['verify' => true]);

Route::get('admin/login',[App\Http\Controllers\Admin\AuthController::class,'loginform'])->name('admin.login.form');
Route::post('admin/login',[App\Http\Controllers\Admin\AuthController::class,'login'])->name('admin-login');

Route::get('partner/login',[App\Http\Controllers\Partner\AuthController::class,'loginform'])->name('partner.login.form');
Route::post('partner/login',[App\Http\Controllers\Partner\AuthController::class,'login'])->name('partner-login');

Route::get('customer/login',[App\Http\Controllers\Customer\AuthController::class,'loginform'])->name('customer.login.form');
Route::post('customer/login',[App\Http\Controllers\Customer\AuthController::class,'login'])->name('customer-login');

// Route::get('/user/register', [App\Http\Controllers\AuthController::class, 'register_form'])->name('register_form');
Route::post('/user/register', [App\Http\Controllers\AuthController::class, 'register'])->name('user.register');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['prefix'=>'admin','middleware'=>['auth','is_admin']],function(){
    Route::get('/file-manager',function(){
        return view('admin.layouts.file-manager');
    })->name('file-manager');
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('users',\App\Http\Controllers\Admin\UsersController::class);
    // Settings
    Route::get('settings',[\App\Http\Controllers\Admin\DashboardController::class,'settings'])->name('settings');
    Route::post('setting/update',[\App\Http\Controllers\Admin\DashboardController::class,'settingsUpdate'])->name('settings.update');
    // Profile
    Route::get('/profile',[\App\Http\Controllers\Admin\DashboardController::class,'profile'])->name('admin-profile');
    Route::post('/profile/{id}',[\App\Http\Controllers\Admin\DashboardController::class,'profileUpdate'])->name('admin-profile-update');

    // Notification
    Route::get('/notification/{id}',[\App\Http\Controllers\NotificationController::class,'show'])->name('admin.notification');
    Route::get('/notifications',[\App\Http\Controllers\NotificationController::class,'index'])->name('all.notification');
    Route::delete('/notification/{id}',[\App\Http\Controllers\NotificationController::class,'delete'])->name('notification.delete');
    // Password Change
    Route::get('change-password', [\App\Http\Controllers\Admin\DashboardController::class,'changePassword'])->name('change.password.form');
    Route::post('change-password', [\App\Http\Controllers\Admin\DashboardController::class,'changPasswordStore'])->name('change.password');

});


Route::group(['prefix'=>'partner','middleware'=>['verified','is_partner']],function(){
    Route::get('/file-manager',function(){
        return view('admin.layouts.file-manager');
    })->name('file-manager');
    Route::get('/dashboard', [App\Http\Controllers\Partner\DashboardController::class, 'index'])->name('partner.dashboard');
    
    // Profile
    Route::get('/profile',[\App\Http\Controllers\Partner\DashboardController::class,'profile'])->name('partner-profile');
    Route::post('/profile/{id}',[\App\Http\Controllers\Partner\DashboardController::class,'profileUpdate'])->name('partner-profile-update');

    // Notification
    Route::get('/notification/{id}',[\App\Http\Controllers\NotificationController::class,'show'])->name('partner.notification');
    Route::get('/notifications',[\App\Http\Controllers\NotificationController::class,'index'])->name('all.notification');
    Route::delete('/notification/{id}',[\App\Http\Controllers\NotificationController::class,'delete'])->name('notification.delete');
    // Password Change
    Route::get('change-password', [\App\Http\Controllers\Partner\DashboardController::class,'changePassword'])->name('partner.change.password.form');
    Route::post('change-password', [\App\Http\Controllers\Partner\DashboardController::class,'changPasswordStore'])->name('partner.change.password');

});


// customer
Route::group(['prefix'=>'customer','middleware'=>['verified','is_customer']],function(){
    Route::get('/file-manager',function(){
        return view('admin.layouts.file-manager');
    })->name('file-manager');
    Route::get('/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('customer.dashboard');
    
    // Profile
    Route::get('/profile',[\App\Http\Controllers\Customer\DashboardController::class,'profile'])->name('customer-profile');
    Route::post('/profile/{id}',[\App\Http\Controllers\Customer\DashboardController::class,'profileUpdate'])->name('customer-profile-update');

    // Notification
    Route::get('/notification/{id}',[\App\Http\Controllers\NotificationController::class,'show'])->name('customer.notification');
    Route::get('/notifications',[\App\Http\Controllers\NotificationController::class,'index'])->name('all.notification');
    Route::delete('/notification/{id}',[\App\Http\Controllers\NotificationController::class,'delete'])->name('notification.delete');
    // Password Change
    Route::get('change-password', [\App\Http\Controllers\Customer\DashboardController::class,'changePassword'])->name('customer.change.password.form');
    Route::post('change-password', [\App\Http\Controllers\Customer\DashboardController::class,'changPasswordStore'])->name('customer.change.password');

});
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
