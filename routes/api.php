<?php

Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function () {
    Route::get('/open', ['middleware' => ['auth'], 'as' => 'get.open.session', 'uses' => 'ApiController@getOpenSession']);
    Route::get('/close', ['as' => 'get.close.session', 'uses' => 'ApiController@getCloseSession']);
    Route::get('/exit', ['as' => 'get.close.exit', 'uses' => 'ApiController@getExitSession']);
    Route::get('/sessions', ['as' => 'get.sessions', 'uses' => 'ApiController@getListSessions']);
    Route::get('/start', ['as' => 'get.start.session', 'uses' => 'ApiController@getStartSession']);
});
