<?php

class Post extends Eloquent {
	protected $guarded = array();
	
	public function tags()
	{
		return $this->morphToMany('Tag', 'taggable');
	}
	
	public function images()
	{
		return $this->morphToMany('Image', 'imageable');
	}
	
	public static function validate($input, $id = null)
	{
		$rules = array(
		//ignore uniqueness for given id
		'title' => 'Required|unique:posts,title,'.$id,
		'intro' => 'Required',
		'body' 	=> 'Required'
		);
		
		return Validator::make($input, $rules);
	}
}
