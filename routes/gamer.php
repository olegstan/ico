<?php
Route::group(['middleware' => ['gamer'], 'prefix' => 'gamer', 'as' => 'gamer.'], function () {
    Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);

    Route::resource('sessions', 'Gamer\SessionsController');
});
