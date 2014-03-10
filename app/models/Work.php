<?php

class Work extends Eloquent {
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
		'title' => 'required|unique:works,title,'.$id,
		'lg_description' 	=> 'required',
		'img_order'	=> 'required',
		'thumbnail_filepath'	=> 'required',
		);
		
		$v = Validator::make($input, $rules);
		$v->sometimes('reason', 'required', function($input)
		{
			return $input->featured;
		});
		
		return $v;
	}
}
