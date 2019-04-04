<?php

// 首页
Route::get('/', 'PagesController@root')->name('root');

// 登录
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// 注册
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// 重置密码
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// 用户相关操作
Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);

// 话题相关操作
Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');

// 话题分类
Route::resource('categories', 'CategoriesController', ['only' => ['show']]);

// 上传功能
Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');

// 回复话题
Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);

// 消息通知
Route::resource('notifications', 'NotificationsController', ['only' => ['index']]);

Route::get('permission-denied', 'PagesController@permissionDenied')->name('permission-denied');