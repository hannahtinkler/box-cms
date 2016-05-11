<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');
Route::get('/random', 'HomeController@getRandomPage');
Route::get('/switchcategory/{id}', 'HomeController@switchCategory');

Route::get('/search/{query}', 'SearchController@performSearch');
Route::get('/search/{query}/results', 'SearchController@showSearchResults');

Route::get('/page/create', 'PageController@create');
Route::get('/page/preview/{id}', 'PageController@previewPage');
Route::post('/page/save', 'PageController@save');
Route::post('/page/preview/save/{id?}', 'PageController@savePreview');

Route::get('/bookmarks', 'BookmarkController@index');
Route::get('/bookmarks/create/{categorySlug}/{chapterSlug}/{pageSlug?}', 'BookmarkController@create');
Route::get('/bookmarks/delete/{categorySlug}/{chapterSlug}/{pageSlug?}', 'BookmarkController@delete');

Route::get('/ajax/modal/server/{id}', 'ServerController@showServerModal');
Route::get('/ajax/data/chapters/{category_id}', 'ChapterController@getChaptersForCategory');

//Data driven pages requiring controllers
Route::get('/p/mayden/servers/server-details/{id?}', 'ServerController@showPage');
Route::get('/p/mayden/servers/ssh-config-generator', 'ServerController@configGenerator');
Route::post('/p/mayden/servers/ssh-config-generator', 'ServerController@generateConfig');
Route::get('/p/iaptus/services/service-list/{id?}', 'ServiceController@showPage');

//Static content pages - catch all
Route::get('/p/{categorySlug}/{chapterSlug}/{pageSlug}', 'PageController@show');
Route::get('/p/{categorySlug}/{chapterSlug}', 'ChapterController@show');
Route::get('/p/{categorySlug}/', 'CategoryController@show');
