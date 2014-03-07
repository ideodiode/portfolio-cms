<?php

class PostsController extends BaseController {


	public function publicIndex()
	{
        Return View::make('posts.public')
			->with('posts', Post::with('tags')->get());
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        Return View::make('posts.index')
			->with('posts', Post::with('tags')->paginate(10));
	}
	
	/**
	 * Display settings for adjusting posts configurations
	 *
	 * @return Response
	 */
	public function getSettings()
	{	
	
		if (Setting::has('post.categoryIDs') && count(Setting::get('post.categoryIDs')) != 0)
			$relatedTagIDs = Setting::get('post.categoryIDs');
		else
			$relatedTagIDs = array();
		
		$tagsLists = Tag::getLists($relatedTagIDs);
		$tags = $tagsLists['tags'];
		$relatedTags = $tagsLists['relatedTags'];
		$relatedTagsString = $tagsLists['relatedTagsString'];
		
        return View::make('posts.settings')
			->with('tags', $tags)
			->with('relatedTags', $relatedTags)
			->with('relatedTagsString', $relatedTagsString);
	}
	
	public function setSettings()
	{
		if (Input::has('tags')){
			$tagInput = explode("#", Input::get('tags'));
			$tagInput = array_slice($tagInput, 1);
			$tagIDs = Tag::whereIn('name',$tagInput)->lists('id');
			
			Setting::set('post.categoryIDs', $tagIDs);
		}
		else
			Setting::set('post.categoryIDs', '');
			
		Return Redirect::action('PostsController@index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('posts.create')
			->with('tags', Tag::select('name')
				->orderBy('name', 'asc')->get());
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		
		$v = Post::validate(Input::all());
		if ( $v->passes() )
		{
			$post = new Post;
			$post->title	= Input::get('title');
			$post->intro	= Input::get('intro');
			$post->body		= Input::get('body');
			$post->save();
			
			// Sync tags from input
			if (Input::has('tags'))
			{
				$tagInput = explode("#", Input::get('tags'));
				$tagInput = array_slice($tagInput, 1);
				$tags = Tag::whereIn('name',$tagInput)->lists('id');
				
				$post->tags()->sync($tags);
			}
			
			// Find all image names from our server imbedded in the post
			$imagesURL = url('images', $parameters = array(), $secure = null);
			preg_match_all("#(?<=\Q".$imagesURL."/\E)(?:works\\/)?[^\"]+(?=\")#", $post->intro, $introImageNames, PREG_PATTERN_ORDER);
			preg_match_all("#(?<=\Q".$imagesURL."/\E)(?:works\\/)?[^\"]+(?=\")#", $post->body, $bodyImageNames, PREG_PATTERN_ORDER);
			$postImageNames = array_merge($introImageNames[0], $bodyImageNames[0]);
			
			if(!empty($postImageNames))
			{
				// Search for images in DB and sync to $post
				$imageIDs = Image::whereIn('name', $postImageNames)->lists('id');
				$post->images()->sync($imageIDs);
			}
			
			Return Redirect::action('PostsController@index');
		}
		else
		{
			return Redirect::action('PostsController@create')
				->withErrors($v)
				->withInput();
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$post = Post::findOrFail($id);
        return View::make('posts.show')
			->with('post', $post)
			->with('tags', $post->tags()->get());
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$post = Post::findOrFail($id);
		
		$relatedTagIDs = DB::table('taggables')->where('taggable_id', '=', $id)->where('taggable_type', '=', 'post')->lists('tag_id');
		$tagsLists = Tag::getLists($relatedTagIDs);
		$tags = $tagsLists['tags'];
		$relatedTags = $tagsLists['relatedTags'];
		$relatedTagsString = $tagsLists['relatedTagsString'];

        return View::make('posts.edit')
			->with('post', $post)
			->with('tags', $tags)
			->with('relatedTags', $relatedTags)
			->with('relatedTagsString', $relatedTagsString);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$v = Post::validate(Input::all(), $id);
		
		if($v->passes())
		{
			$post = Post::findOrFail($id);
			$post->title	= Input::get('title');
			$post->intro	= Input::get('intro');
			$post->body		= Input::get('body');
			$post->save();
			
			// Sync tags from input
			if (Input::has('tags'))
			{
				$tagInput = explode("#", Input::get('tags'));
				$tagInput = array_slice($tagInput, 1);
				$tags = Tag::whereIn('name',$tagInput)->lists('id');
				
				$post->tags()->sync($tags);
			}
			
			// Find all image names from our server imbedded in the post
			$imagesURL = url('images', $parameters = array(), $secure = null);
			preg_match_all("#(?<=\Q".$imagesURL."/\E)(?:works\\/)?[^\"]+(?=\")#", $post->intro, $introImageNames, PREG_PATTERN_ORDER);
			preg_match_all("#(?<=\Q".$imagesURL."/\E)(?:works\\/)?[^\"]+(?=\")#", $post->body, $bodyImageNames, PREG_PATTERN_ORDER);
			$postImageNames = array_merge($introImageNames[0], $bodyImageNames[0]);
			
			if(!empty($postImageNames))
			{
				// Search for images in DB and sync to $post
				$imageIDs = Image::whereIn('name', $postImageNames)->lists('id');
				$post->images()->sync($imageIDs);
			}
			
			Return Redirect::action('PostsController@index');
		}
		else
		{
			Return Redirect::action('PostsController@edit', $id)
				->withInput()
				->withErrors($v);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$post = Post::findOrFail($id);
		$post->tags()->detach();
		$post->delete();
		
		Return Redirect::action('PostsController@index');
	}

}
