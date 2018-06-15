<?php
Route::group(['middleware' => ['gamer'], 'prefix' => 'gamer', 'as' => 'gamer.'], function () {
    Route::get('/home', 'HomeController@index');

    Route::resource('sessions', 'Admin\SessionsController');
});
