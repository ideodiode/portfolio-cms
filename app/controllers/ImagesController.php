<?php

class ImagesController extends BaseController {
		
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return View::make('images.index')
			->with('images', Image::select('id', 'name', 'path')->orderBy('name', 'asc')->get());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('images.create');
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
			$action = Input::get('action');
			
			
			// If temporary place in /temp
			if($action == 'temp')
			{
				$file = Input::file('file');
				$filename = $file->getClientOriginalName();
				$filePath = '/images/temp/';
				
				$file->move(public_path().$filePath, $filename);
				
				return Response::json(array(
					'passes'=> true, 
					'url'	=> asset($filePath.$filename),
					'filename'	=> $filename
				));
			}			
			// If works thumbnail, crop, save in /temp
			else if ($action == 'worksThumb')
			{
				$url = Input::get('url');
				$filename = pathinfo($url)['basename'];
				// Get filePath for \services\imagine
				if (strpos($url, '/images/temp/') !== FALSE)
					$filePath = '/images/temp/'.$filename;
				else
					$filePath = '/images/uploads/'.$filename;
				// FilePath to save edited images
				$newFilePath = '/images/temp/';
				
				$filename = Imagine::workThumbnail($newFilePath, $filePath, Input::get('x1'), Input::get('y1'), Input::get('w'), Input::get('h'), 300, Input::get('retina'));
				
				return Response::json(array(
					'passes'=> true, 
					'url'	=> asset($newFilePath.$filename),
					'filename'	=> $filename
				));
			}
			else if ($action == 'workFeatured')
			{
				$file = Input::file('file');
				$filename = $file->getClientOriginalName();
				$filePath = '/images/temp/';
				
				$file->move(public_path().$filePath, $filename);
				
				$filename = Imagine::workFeatured($filePath.$filename, 1000, 500);
				
				return Response::json(array(
					'passes'=> true, 
					'url'	=> asset($filePath.$filename),
					'filename'	=> $filename
				));
			}			
			else if ($action == 'upload')
			{
				$file = Input::file('file');
				$filename = $file->getClientOriginalName();
				$filePath = '/images/uploads/';
				
				$input = array('name' => $filename, 'file' => $file, 'path' => $filePath);
				$v = Image::validate($input);
				
				// If path.filename doesn't exist yet
				if ( $v->passes() )
				{
					//Save in /upload
					$file->move(public_path().$filePath, $filename);
					
					//Create thumbnails
					Imagine::upload($filePath.$filename, 200, 200);
					
					$image = new Image;
					$image->name = $filename;
					$image->path = $filePath;
					$image->save();
					
					return Response::json(array(
						'passes'	=> true, 
						'url'		=> asset($filePath.$filename),
						'filename'	=> $filename,
						'id'		=> $image->id
					));
				}
				// If path.filename already exists
				elseif( $v->messages()->has('name') )
				{
					$imageID = Image::where('name', $filename)->where('path', $filePath)->firstOrFail();
					Return Response::json(array(
						'passes'	=> false,
						'msg'		=> '\''.$filename.'\' already exists. Original file used. Change the filename to attempt again.',
						'url'		=> asset($filePath.$filename),
						'filename'	=> $filename,
						'id'		=> $imageID->id
					));
				}
				// If anything else fails
				else
				{
					Return Response::json(array(
						'passes'=> false,
						'msg'	=> $v->messages()->first()
					));
				}
			}
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
        return View::make('images.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return View::make('images.edit')
			->with('image', Image::findOrFail($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$image = Image::findOrFail($id);
		$imageName = Input::get('name');
		$image->name = $tagName;
		$image->save();
		
		return Redirect::action('ImagesController@index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$image = Image::findOrFail($id);
		$image->delete();
		
		return Redirect::action('ImagesController@index');
	}

}
