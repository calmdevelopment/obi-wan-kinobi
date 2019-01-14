<?php

/*
|--------------------------------------------------------------------------
| Local Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for local development. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
| These routes are prefixed /local/ and not available on production!
|
*/

Route::get('/styleguide', function() {
    return view('styleguide.index');
});
