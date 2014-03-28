<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	$features = Work::where('featured', true)->select('featured_filepath')->get();
	return View::make('hello')
		->with("features", $features);
});
Route::get('contact', function()
{
	return View::make('contact');
});

Route::get('posts', array('uses' => 'PostsController@publicIndex'));
Route::get('works', array('uses' => 'WorksController@publicIndex'));
Route::get('posts/{id}', array('uses' => 'PostsController@show'));
Route::get('works/{id}', array('uses' => 'WorksController@show'));

/*
*	Admin console
*/
Route::when('admin/*', 'csrf', array('post', 'put', 'patch', 'delete'));

Route::group(array('prefix' => 'admin'), function()
{
	Route::get('logout', array('uses' => 'AuthController@getLogout'));
	Route::get('login', array('uses' => 'AuthController@getLogin'));
	Route::post('login', array('uses' => 'AuthController@postLogin'));
	
	Route::group(array('before'=>'auth'), function()
	{
		Route::get('/', function()
		{
			return View::make('admin');
		});
		
		Route::get('works/settings', array('uses' => 'WorksController@getSettings'));
		Route::post('works/settings', array('uses' => 'WorksController@setSettings'));
		Route::resource('works', 'WorksController');

		Route::get('posts/settings', array('uses' => 'PostsController@getSettings'));
		Route::post('posts/settings', array('uses' => 'PostsController@setSettings'));
		Route::resource('posts', 'PostsController');

		Route::resource('tags', 'TagsController');
		
		Route::resource('images', 'ImagesController');
	});
});
