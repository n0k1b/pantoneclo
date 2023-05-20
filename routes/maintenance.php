<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;


Route::get('/maintenance-mode', function() {
    return view('maintenance');
});

Route::get('/optimize', function() {
    Artisan::call('optimize:clear');
    return redirect()->back();
});

Route::get('/db-seed', function() {
    Artisan::call('db:seed --class=NameSeeder');
    return 'DB Seeding Successfully';
});

Route::get('/migrate', function() {
    Artisan::call('migrate');
    return 'Successfully Migrated';
});

Route::get('/maintainance-down', function() {
    Artisan::call('down');
    return redirect()->back();
});

Route::get('/maintainance-up', function() {
    Artisan::call('up');
    return redirect()->back();
});


Route::get('/documentation',function(){
    return File::get(public_path() . '/documentation/index.html');
});
