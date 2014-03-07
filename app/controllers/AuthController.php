<?php 

class AuthController extends BaseController {
	
	/**
	 * Display the login page
	 * @return View
	 */
	public function getLogin()
	{
		if(Auth::user())
			Auth::logout();
		return View::make('adminLogin');
	}
	
	/**
	 * Display the login page
	 * @return View
	 */
	public function postLogin()
	{
		$credentials = array(
			'email'		=> Input::get('email'),
			'password'	=> Input::get('password')
		);
		
		if(Auth::attempt($credentials, Input::has('remember')))
			return Redirect::intended('admin');
		else
			return Redirect::action('AuthController@getLogin')
				->withErrors(array('message' => 'Your username or password are incorrect'))
				->withInput(Input::except('password'));
	}
	
	/**
	 * Logout action
	 * @return Redirect
	 */
	public function getLogout()
	{
		Auth::logout();
		return Redirect::action('AuthController@getLogin');
	}
}