<?php

Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function () {
    Route::get('/open', ['middleware' => ['auth'], 'as' => 'get.open.session', 'uses' => 'ApiController@getOpenSession']);
    Route::get('/close', ['as' => 'post.close.session', 'uses' => 'ApiController@postCloseSession']);
});
