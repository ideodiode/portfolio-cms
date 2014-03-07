<?php

class Image extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	
	public function works()
	{
		return $this->morphedByMany('Work', 'imageable');
	}
	
	public function posts()
	{
		return $this->morphedByMany('Post', 'imageable');
	}
	
	public static function validate($input)
	{		
		$rules = array(
		'name' => 'Required|unique:images,name,NULL,path,path,'.$input['path'],
		'file' => 'image',
		);
		
		return Validator::make($input, $rules);
	}
}
