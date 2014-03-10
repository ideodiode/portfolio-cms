<?php

class WorksController extends BaseController {

	/**
	 * Display a listing of the resource in public layout.
	 *
	 * @return Response
	 */
	public function publicIndex()
	{	
		$choice = 'All Works';
		$categories = array();
		
		if (Setting::has('work.categoryIDs') && count(Setting::get('work.categoryIDs')) != 0){
			$categoryIDs = Setting::get('work.categoryIDs');
			$categories = Tag::whereIn('id', $categoryIDs)->get();
		}			
		
		if (Input::has('category') && Input::get('category') != 'All Works'){
			$choice = Input::get('category');

			$works = Tag::where('name', '=', $choice)
				->leftJoin('taggables', 'tags.id', '=', 'taggables.tag_id')
				->where('taggables.taggable_type', '=', 'Work')
				->leftJoin('works', 'taggables.taggable_id', '=', 'works.id')
				->select('works.id', 'works.title', 'works.thumbnail_filepath')
				->get();
		}
		else
			$works = Work::all();
		
		return View::make('works.public')
			->with('works', $works)
			->with('categories', $categories)
			->with('choice', $choice);
	}
	
	/**
	 * Display settings for adjusting works configurations
	 *
	 * @return Response
	 */
	public function getSettings()
	{	
	
		if (Setting::has('work.categoryIDs') && count(Setting::get('work.categoryIDs')) != 0)
			$relatedTagIDs = Setting::get('work.categoryIDs');
		else
			$relatedTagIDs = array();
		
		$tagsLists = Tag::getLists($relatedTagIDs);
		$tags = $tagsLists['tags'];
		$relatedTags = $tagsLists['relatedTags'];
		$relatedTagsString = $tagsLists['relatedTagsString'];
		
        return View::make('works.settings')
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
			
			Setting::set('work.categoryIDs', $tagIDs);
		}
		else
			Setting::set('work.categoryIDs', '');
			
		Return Redirect::action('WorksController@index');
	}

	
	/**
	 * Display a listing of the resource in requested layout
	 *
	 * @return Response
	 */
	public function index()
	{
		if (Input::has('session')){
			Session::put('works.layout', Input::get('session'));
			return;
		}
		
		$perPage = 20;		
		$layoutType = "";
		$layoutType = Session::get("works.layout"); // Use layout saved in session
		
		if (Input::has('layout_type')){ // Instead use layout if requested
			$layoutType = Input::get('layout_type');
			Session::put('works.layout', $layoutType);
		}
		
		// Return list layout with pagination
		if($layoutType == 'listLayout'){ 
			if (Input::has('featured'))
				$works = Work::where('featured', '=', true)->with('tags')->paginate($perPage);
			else
				$works = Work::with('tags')->paginate($perPage);
			
			$pagination = $works->links();
			
			$view =  View::make('works.layout.list')
				->with('works', $works)
				->with('pagination',$pagination);
		}
		// Return block view
		else{ 
			$layoutType = 'blockLayout';
			if (Input::has('featured'))
				$works = Work::where('featured', '=', true)->get();
			else
				$works = Work::all();
				
			$view =  View::make('works.layout.block')
				->with('works', $works)
				->with('width', 150);
		}

		if(Request::Ajax())
			Return Response::json(array('html'=>$view->render()));
		
		$switches = ['active','checked'];
        return View::make('works.index')
			->with('html', $view->render())
			->with($layoutType, $switches);
	}
	

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		File::cleanDirectory(public_path().'/images/temp');
		$relatedTagIDs = array();
		
		$tagsLists = Tag::getLists($relatedTagIDs);
		$tags = $tagsLists['tags'];
		
        return View::make('works.create')
			->with('tags', $tags);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{		
		$v = Work::validate(Input::all());
		if ($v->passes() )
		{
			
			
			$work = new Work;
			$work->title			  = Input::get('title');
			$work->lg_description	  = Input::get('lg_description');
			$work->img_order		  = Input::get('img_order');
			$work->featured			  = Input::has('feature');
			
			$work->save();
			
			// Move standard thumbnail from /temp to /work & save()
			$path = $this->moveWorkTemp($work, Input::get('thumbnail_filepath'), '_thumb');
			$work->thumbnail_filepath = $path;
			$work->save();
			
			// Move retina thumbnail from /temp to /work & save()
			$path = $this->moveWorkTemp($work, Input::get('thumbnail2x_filepath'), '_thumb2x');
			$work->thumbnail2x_filepath = $path;
			$work->save();
			
			//// Move featured images from /temp to /work & save()
			if(Input::has('featured_filepath')){
				$tempPath = Input::get('featured_filepath');
				$path = $this->moveWorkTemp($work, $tempPath, '_featured');
				$work->featured_filepath = $path;
				$work->save();
				
				$dirname = pathinfo($tempPath)['dirname'];
				$filename = pathinfo($tempPath)['filename'];
				$ext = pathinfo($tempPath)['extension'];
				$temp2xPath = $dirname.'/'.$filename.'2x.'.$ext;
				
				$this->moveWorkTemp($work, $temp2xPath, '_featured2x');
			}
			
			// Sync tags from input
			if (Input::has('tags'))
			{
				$tagInput = explode("#", Input::get('tags'));
				$tagInput = array_slice($tagInput, 1);
				$tags = Tag::whereIn('name',$tagInput)->lists('id');
				
				$work->tags()->sync($tags);
			}
			
			
			if(Input::has('img_order'))
			{
				// Search for images in DB and sync to $work
				$imageIDs = explode("/", Input::get('img_order'));
				$imageIDs = array_slice($imageIDs, 1);
				$work->images()->sync($imageIDs);
			}
			
			Return Redirect::action('WorksController@index');
		}
		else
		{
			return Redirect::action('WorksController@create')
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
		$work = Work::findOrFail($id);
		$tags = $work->tags()->get();
		if($work->img_order)
			$imagesOrdered = $this->getImagesOrdered($work);
		
        return View::make('works.show')
			->with('work', $work)
			->with('tags', $tags)
			->with('images', $imagesOrdered);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $work = Work::findOrFail($id);	
		// Retrieve images in order
		if(strlen($work->img_order) != 0)
			$imagesOrdered = $this->getImagesOrdered($work);
		
		$relatedTagIDs = DB::table('taggables')->where('taggable_id', '=', $id)->where('taggable_type', '=', 'work')->lists('tag_id');
		$tagsLists = Tag::getLists($relatedTagIDs);
		$tags = $tagsLists['tags'];
		$relatedTags = $tagsLists['relatedTags'];
		$relatedTagsString = $tagsLists['relatedTagsString'];
		
		
        return View::make('works.edit')
			->with('work', $work)
			->with('images', $imagesOrdered)
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
		$v = Work::validate(Input::all(), $id);
		if ($v->passes() )
		{
			$work = Work::findOrFail($id);
			$work->title			  = Input::get('title');
			$work->lg_description	  = Input::get('lg_description');
			$work->img_order		  = Input::get('img_order');
			$work->featured			  = Input::has('feature');
			
			$work->save();
			
			
			
			// Move standard thumbnail from /temp to /work & save()
			$path = $this->moveWorkTemp($work, Input::get('thumbnail_filepath'), '_thumb');
			$work->thumbnail_filepath = $path;
			$work->save();
			
			// Move retina thumbnail from /temp to /work & save()
			$path = $this->moveWorkTemp($work, Input::get('thumbnail2x_filepath'), '_thumb2x');
			$work->thumbnail2x_filepath = $path;
			$work->save();
			
			//// Move featured images from /temp to /work & save()
			if(Input::has('featured_filepath')){
				$tempPath = Input::get('featured_filepath');
				$path = $this->moveWorkTemp($work, $tempPath, '_featured');
				$work->featured_filepath = $path;
				$work->save();
				
				$dirname = pathinfo($tempPath)['dirname'];
				$filename = pathinfo($tempPath)['filename'];
				$ext = pathinfo($tempPath)['extension'];
				$temp2xPath = $dirname.'/'.$filename.'2x.'.$ext;
				
				$path = $this->moveWorkTemp($work, $temp2xPath, '_featured2x');
			}
			else{
				$work->featured_filepath = $path;
				$work->save();
			}
			
			// Sync tags from input
			if (Input::has('tags'))
			{
				$tagInput = explode("#", Input::get('tags'));
				$tagInput = array_slice($tagInput, 1);
				$tags = Tag::whereIn('name',$tagInput)->lists('id');
				
				$work->tags()->sync($tags);
			}
			else
				$work->tags()->sync(array());
			
			if(strlen(Input::get('img_order')) != 0)
			{
				// Search for images in DB and sync to $work
				$imageIDs = explode("/", Input::get('img_order'));
				$imageIDs = array_slice($imageIDs, 1);
				$work->images()->sync($imageIDs);
			}
			else
				$work->images()->sync(array());
			
			
			Return Redirect::action('WorksController@index');
		}
		else
		{
			return Redirect::action('WorksController@edit')
				->withErrors($v)
				->withInput();
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
		$work = Work::findOrFail($id);
		$work->tags()->detach();
		if($work->featured_filepath != null)
			File::delete(public_path().$work->featured_filepath);
		File::delete(public_path().$work->thumbnail2x_filepath);
		File::delete(public_path().$work->thumbnail_filepath);
		$work->delete();
		
		Return Redirect::action('WorksController@index');
	}
	
	/**
	 * Move standard & retina thumbnails from /temp to /work
	 *
	 * @param  int  $id
	 * @param  string  $input
	 * @return string $workThumbPath
	 */
	private function moveWorkTemp($work, $input, $nameMod)
	{
		// Move thumbnail, save to model
		$extension = pathinfo($input)['extension'];
		$workPath = "/images/works/".$work->id.$nameMod.'.'.$extension;
		File::copy(public_path().$input, public_path().$workPath);
		
		return $workPath;	
	}

	/**
	 * Re-order associated images according to img_order
	 *
	 * @param  Work  $work
	 * @return Collection $imagesOrdered
	 */
	private function getImagesOrdered($work)
	{
		// Pull array[(int)$id] from img_order
		$imgOrder = $work->img_order;
		$imgOrder = explode("/", $imgOrder);
		$imgOrder = array_slice($imgOrder, 1);
		$imgOrder = array_map('intval', $imgOrder);
		
		$images = Image::whereIn('id', $imgOrder)->get();
		
		// Get $idOrder[$id => $index]
		$idOrder = $images->lists('id');
		$idOrder = array_flip($idOrder);
		
		$imagesOrdered = new Illuminate\Database\Eloquent\Collection();
		foreach($imgOrder as $id){
			$index = $idOrder[$id];
			$imagesOrdered->add($images->offsetGet($index));
		}
		
		return $imagesOrdered;
	}
}
