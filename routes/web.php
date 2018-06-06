<?php
Route::get('/', 'LobbyController@getIndex');
Route::get('/games', ['as' => 'games', 'uses' => 'LobbyController@getGames']);

// Authentication Routes...
Route::any('/wp-admin/admin-ajax.php', 'Auth\LoginController@anyForm')->name('login');

//$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
//$this->post('login', 'Auth\LoginController@login')->name('auth.login');
//$this->post('logout', 'Auth\LoginController@logout')->name('auth.logout');

// Change Password Routes...
//$this->get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
//$this->patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

// Password Reset Routes...
//$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
//$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.password.reset');
//$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//$this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('auth.password.reset');
//

