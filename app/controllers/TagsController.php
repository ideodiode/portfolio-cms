<?php

class TagsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$tags = Tag::select(array('tags.id', 'tags.name', DB::raw('COUNT(taggables.tag_id) as count')))
				->leftJoin('taggables', 'tags.id', '=', 'taggables.tag_id')
				->groupBy('tags.name')
				->orderBy('tags.name', 'asc')
				->get();
				
        return View::make('tags.index')
			->with('tags', $tags);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('tags.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if(Request::Ajax())
		{
			$v = Tag::validate(Input::all());
			if ( $v->passes() )
			{
				$tag = new Tag;
				$tag->name	= Input::get('name');
				$tag->save();
				
				Return Response::json(array("passes"=>true, "id"=>$tag->id));
			}
			else
			{
				Return Response::json(array("passes"=>false,"msg"=>$v->messages()->first('name')));
			}
		}
	}

	/**
	 * Display view containing table with specified taggable.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$perPage = 10;
		$taggableType = Input::get('taggableType');
		$tag = Tag::findOrFail($id);
		$postIDs = array();
		$workIDs = array();
		if ($taggableType == 'all'){
			// Retrieve paginated taggables
			$taggables = DB::table('taggables')
				->where('tag_id', '=', $id)
				->select('taggable_id', 'taggable_type')
				->paginate($perPage);
			$pagination = $taggables->links();
			
			// Sort taggables into types
			foreach($taggables as $taggable){
				if($taggable->taggable_type == "post")
					$postIDs[] = $taggable->taggable_id;
				else
					$workIDs[] = $taggable->taggable_id;
			}
			$posts = new Illuminate\Database\Eloquent\Collection();
			$works = new Illuminate\Database\Eloquent\Collection();
			
			$taggables = new Illuminate\Database\Eloquent\Collection();
			
			if(!empty($postIDs)){
				$posts = Post::whereIn('id',$postIDs)->get();
				
				foreach($posts as $post){
					$post['taggable_type'] = 'post';
					$taggables->add($post);
				}
			}
			if(!empty($workIDs)){
				$works = Work::whereIn('id', $workIDs)->get();
				
				foreach($works as $work){
					$work['taggable_type'] = 'work';
					$taggables->add($work);
				}
			}
			
			$taggables = $taggables->sortBy(function($taggable){
				return $taggable->created_at;
			});
		}
		elseif ($taggableType == 'posts'){
			$taggables = $tag->posts()->with('tags')
				->orderBy('created_at', 'desc')
				->paginate($perPage);
			$pagination = $taggables->links();
		}
		else{
			$taggables = $tag->works()->with('tags')
				->orderBy('created_at', 'desc')
				->paginate($perPage);
			$pagination = $taggables->links();
		}
		
		$view = View::make('tags.taggableTable')
				->with('taggables', $taggables)
				->with('pagination',$pagination);
				
		if(Request::Ajax())
			Return Response::json(array('html'=>$view->render()));
			
        return $view;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return View::make('tags.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$v = Tag::validate(Input::all(), $id);
		if ( $v->passes() )
		{
			$tag = Tag::findOrFail($id);
			$tagName = Input::get('name');
			$tag->name = $tagName;
			$tag->save();
			
			return Redirect::action('TagsController@index');
		}
		else
		{
			return Redirect::action('TagsController@index')->withErrors($v);
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
		$tag = Tag::findOrFail($id);
		$tag->posts()->detach();
		$tag->works()->detach();
		$tag->delete();
		
		return Redirect::action('TagsController@index');
	}

}
