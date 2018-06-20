<?php
Route::group(['middleware' => ['admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', ['as' => 'admin', 'uses' => 'HomeController@index']);

    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    Route::resource('users', 'Admin\UsersController');
    Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
    Route::resource('games', 'Admin\GamesController');
    Route::post('games_mass_destroy', ['uses' => 'Admin\GamesController@massDestroy', 'as' => 'games.mass_destroy']);
    Route::resource('sessions', 'Admin\SessionsController');
    Route::post('sessions_mass_destroy', ['uses' => 'Admin\SessionsController@massDestroy', 'as' => 'sessions.mass_destroy']);
    Route::resource('configs', 'Admin\ConfigsController');
    Route::post('configs_mass_destroy', ['uses' => 'Admin\ConfigsController@massDestroy', 'as' => 'configs.mass_destroy']);
});
