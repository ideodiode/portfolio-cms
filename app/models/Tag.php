<?php

class Tag extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

	
	public function works()
	{
		return $this->morphedByMany('Work', 'taggable');
	}
	
	public function posts()
	{
		return $this->morphedByMany('Post', 'taggable');
	}
	
	/**
	 * Get tag lists associated with $taggable for /tags/panel.blade.php
	 *
	 * @param  array  $relatedTagIDs
	 *
	 * @return array $tagData{
	 *					array  $tags,
	 *					array  $relatedTags,
	 *					string $relatedTagsString}
	 */
	public static function getLists($relatedTagIDs)
	{
		$relatedTags = new Illuminate\Database\Eloquent\Collection();
		$tags = new Illuminate\Database\Eloquent\Collection();
		$relatedTagsString = null;
		
		$allTags = Tag::select(array('tags.id', 'tags.name', DB::raw('COUNT(taggables.tag_id) as count')))
			->leftJoin('taggables', 'tags.id', '=', 'taggables.tag_id')
			->groupBy('tags.name')
			->orderBy('tags.name', 'asc')
			->get();
		
		if (!empty($relatedTagIDs))
		{
			foreach($allTags as $tag){
				if(in_array($tag->id, $relatedTagIDs))
					$relatedTags->add($tag);
				else
					$tags->add($tag);
			};
			
			$relatedTagsString = array_reduce($relatedTags->lists('name'), function($r, $x){
					return $r .= "#".$x;
				}
			);
		}
		else
			$tags = $allTags;
		
		$tagData = [
			'tags' => $tags,
			'relatedTags' => $relatedTags,
			'relatedTagsString' => $relatedTagsString
		];
		return $tagData;
		
	}
	
	
	public static function validate($input, $id = null)
	{
		$rules = array(
		//ignore uniqueness for given id
		'name' => 'required|alpha_dash|unique:tags,name,'.$id
		);
		
		return Validator::make($input, $rules);
	}
}
