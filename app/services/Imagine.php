<?php namespace App\Services;

use Config, File, Log;

class Imagine {
	
	/**
     * Instance of the Imagine package
     * @var Imagine\Gd\Imagine
     */
    protected $imagine;
 
    /**
     * Type of library used by the service
     * @var string
     */
    protected $library;
 
    /**
     * Initialize the image service
     * @return void
     */
    public function __construct()
    {
        if ( ! $this->imagine)
        {
            $this->library = Config::get('imagine.library', 'gd');
 
            // Create the instance
            if     ($this->library == 'imagick') 
				$this->imagine = new \Imagine\Imagick\Imagine();
            elseif ($this->library == 'gmagick')
				$this->imagine = new \Imagine\Gmagick\Imagine();
            elseif ($this->library == 'gd')
				$this->imagine = new \Imagine\Gd\Imagine();
            else
				$this->imagine = new \Imagine\Gd\Imagine();
        }
    }
	/**
	 * Resize an image
	 * @param  string $url
	 * @param  integer $width
	 * @param  integer $height
	 * @return string $url
	 */
	public function upload($url, $width = 200, $height = 200, $quality = 75)
	{
		// Quality
		$quality = Config::get('image.quality', $quality);
		
		// URL info
		$info = pathinfo($url);
		
		// Directories and file names
		$baseName       = $info['basename'];
		$filename		= $info['filename'];
		$extension 		= $info['extension'];
		
		$sourceDirPath  = public_path() . $info['dirname'];
		$sourceFilePath = $sourceDirPath . '/' . $baseName;
		//try
		//{
			$image = $this->imagine->open($sourceFilePath);
			
			// Resize for height			
			$targetDirName  = 'yThumbs';
			$targetDirPath  = $sourceDirPath . '/' . $targetDirName . '/';
			//Standard
			$size = $image->getSize()->heighten($height);
			$targetFilePath = $targetDirPath . $baseName;
			$this->saveThumbnail($image, $targetFilePath, $extension, $size, $quality);
			//Retina
			$size = $image->getSize()->heighten($height*2);
			$targetFilePath = $targetDirPath . $filename . '@2x.' . $extension;
			$this->saveThumbnail($image, $targetFilePath, $extension, $size, $quality);
			
			// Resize for width
			$targetDirName  = 'xThumbs';
			$targetDirPath  = $sourceDirPath . '/' . $targetDirName . '/';
			//Standard
			$size = $image->getSize()->widen($width);
			$targetFilePath = $targetDirPath . $baseName;
			$this->saveThumbnail($image, $targetFilePath, $extension, $size, $quality);
			//Retina
			$size = $image->getSize()->widen($width*2);
			$targetFilePath = $targetDirPath . $filename . '@2x.' . $extension;
			$this->saveThumbnail($image, $targetFilePath, $extension, $size, $quality);
		/*}
		catch (\Exception $e)
		{
			Log::error('[IMAGE SERVICE] Failed to resize image "' . $url . '" [' . $e->getMessage() . ']');
		}*/
	}

	private function saveThumbnail($image, $targetFilePath, $extension, $size, $quality)
	{
		// If animated gif, resize each layer first, then save
		if($extension == "gif" && count($image->layers()) > 1)
		{
			$image->thumbnail($size)->save($targetFilePath, array('flatten' => 'false'));
		}
		// All other images resized and saved
		else
		{
			$image->thumbnail($size)
				->save($targetFilePath, array('quality' => $quality));
		}
	}

	public function workThumbnail($newPath, $filePath, $cropX1, $cropY1, $cropW, $cropH, $width, $retina, $quality = 75)
	{
		// Quality
		$quality = Config::get('image.quality', $quality);
		
		// URL info
		$info = pathinfo($filePath);
		
		// Directories and file names
		$baseName       = $info['basename'];
		$filename		= $info['filename'];
		$extension 		= $info['extension'];
		$sourcePath		= public_path() . $filePath;
		try
		{
			$image = $this->imagine->open($sourcePath);
			$targetDirPath  = public_path() . $newPath;
			
			//Change path and final width based on $retina
			if($retina == "true"){
				$filename .= "_thumb@2x.".$extension;
				$targetFilePath = $targetDirPath . $filename;
				$width = $width*2;
			} else {
				$filename .= "_thumb.".$extension;
				$targetFilePath = $targetDirPath . $filename;
			}
			
			$size = new \Imagine\Image\Box($cropW, $cropH);
			$start = new \Imagine\Image\Point($cropX1, $cropY1);
			
			if(File::isFile($targetFilePath))
			{
				File::delete($targetFilePath);
			}
			// If animated gif, crop each layer first, then save
			if($extension == "gif" && count($image->layers()))
			{
				$image = $image->strip()->crop($start, $size);
				$finalSize = $image->getSize()->widen($width);
				
				$image->resize($finalSize)->save($targetFilePath, array('flatten' => 'false', 'quality' => $quality));
			}
			// All other images cropped and saved
			else
			{
				$image->strip()->crop($start, $size);
				$finalSize = $image->getSize()->widen($width);
				$image->resize($finalSize)->save($targetFilePath, array('quality' => $quality));
			}
		}
		catch (\Exception $e)
		{
			Log::error('[IMAGE SERVICE] Failed to resize image "' . $filePath . '" [' . $e->getMessage() . ']');
		}
		
		return $filename;
	}
	
	public function workFeatured($filePath, $width, $height, $quality = 75)
	{
		// Quality
		$quality = Config::get('image.quality', $quality);
		
		// URL info
		$info = pathinfo($filePath);
		
		// Directories and file names
		$filename		= $info['filename'];
		$basename		= $info['basename'];
		$extension 		= $info['extension'];
		$pathname		= $info['dirname'];
		$sourcePath		= public_path() . $pathname . '/';
		//try
		//{
			$image = $this->imagine->open($sourcePath.$basename);
			
			$filenameS = $filename."_featured.".$extension;
			$targetFilePath = $sourcePath . $filenameS;
			$this->saveFeatured($image, $targetFilePath, $extension, $width, $height, $quality);
			
			$filenameR = $filename."_featured2x.".$extension;
			$targetFilePath = $sourcePath . $filenameR;
			$width = $width*2;
			$height = $height*2;
			$this->saveFeatured($image, $targetFilePath, $extension, $width, $height, $quality);
			
			
		//}
		//catch (\Exception $e)
		//{
		//	Log::error('[IMAGE SERVICE] Failed to resize image "' . $filePath . '" [' . $e->getMessage() . ']');
		//}
		
		return $filenameS;
	}
	
	private function saveFeatured($image, $targetFilePath, $extension, $width, $height, $quality)
	{
		$point = new \Imagine\Image\Point(0,0);
		// If animated gif, resize each layer first, then save
		if($extension == "gif" && count($image->layers()) > 1)
		{
			$finalSize = $image->getSize()->widen($width);
			$crop = new \Imagine\Image\Box($finalSize->getWidth(), $height);
			$image = $image->strip()->resize($finalSize)->crop($point, $crop);

			$image->save($targetFilePath, array('flatten' => 'false', 'quality' => $quality));
		}
		// All other images cropped and saved
		else
		{
			$finalSize = $image->getSize()->widen($width);
			$crop = new \Imagine\Image\Box($finalSize->getWidth(), $height);
			$image->strip()->resize($finalSize)->crop($point, $crop)->save($targetFilePath, array('quality' => $quality));
		}
	}
}
	