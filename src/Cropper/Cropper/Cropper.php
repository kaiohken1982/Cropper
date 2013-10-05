<?php 
namespace Cropper\Cropper;

class Cropper 
	implements CropperInterface 
{
    /**
     * @const cropper types
     */
    const CROPPER_TYPE_CENTER = 1;
    const CROPPER_TYPE_TOP = 2;
    
	/**
	 * The thumnailer service
	 * @var object
	 */
	private $thumbnailerService;
    
	/**
	 * The opened (source) image
	 * @var string
	 */
	private $sourceImagePath;
	
	/**
	 * Source Image resource
	 * @var resource
	 */
	private $sourceImageResource;
	
	/**
	 * Full path of the image that will be watermarkeds
	 * @var string
	 */
	private $imageFullPath;
	
	/**
	 * Info of the image to be watermarked
	 * @var null|array
	 */
	private $imageInfo;
	
	/**
	 * Image extension
	 * @var string
	 */
	private $extension;
	
	/**
	 * Destination width
	 * @var int
	 */
	private $width;
	
	/**
	 * Destination height
	 * @var int
	 */
	private $height;
	
	/**
	 * Crop a square part of the source image 
	 * where l = source width
	 * @var bool
	 */
	private $squareMode = false;
	
	/**
	 * Class construct
	 * Set the thumbnailer service.
	 * 
	 * @param object $thumbnailerService
	 */
	public function __construct($thumbnailerService) 
	{
		$this->thumbnailerService = $thumbnailerService;
		$this->setType(self::CROPPER_TYPE_CENTER);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Cropper\Cropper\CropperInterface::parseConfig()
	 */
	public function parseConfig($config) 
	{
        return $this;
	}
	
	/**
	 * Get the watermark type
	 * 
	 * @return int
	 */
	public function getType() 
	{
		return $this->type;
	}
	
	/**
	 * Set the cropping type
	 * 
	 * @return \Cropper\Cropper\Cropper
	 */
	public function setType($type) 
	{
		$this->type = $type;
		
		return $this;
	}
    
    /**
     * Set extension value
     * 
     * @return \Cropper\Cropper\Cropper
     */
    public function setExtension($extension) 
    {
    	$this->extension = strtolower($extension);
    }
    
    /**
     * Get extension value
     * 
     * @return string
     */
    public function getExtension()
    {
    	return $this->extension;
    }
    
    /**
     * Set square mode
     * 
     * @return \Cropper\Cropper\Cropper
     */
    public function setSquareMode($squareMode) 
    {
    	$this->squareMode = $squareMode;
    }
    
    /**
     * Get square mode
     * 
     * @return bool
     */
    public function getSquareMode()
    {
    	return $this->squareMode;
    }
	
	/**
	 * (non-PHPdoc)
	 * @see \Cropper\Cropper\CropperInterface::open()
	 */
	public function open($sourceImagePath) 
	{
		$this->imageFullPath = $sourceImagePath;
		$this->thumbnailerService->open($sourceImagePath);
		$this->sourceImageResource = $this->thumbnailerService->getSourceImageResource();
		$this->setExtension($this->thumbnailerService->getExtension());
		$this->imageInfo = $this->thumbnailerService->getImageInfo();
		
		// If set size is not called by the user, the cropped image will be the same as source
		$this->width = $this->imageInfo['width'];
		$this->height = $this->imageInfo['height'];
		
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Cropper\Cropper\CropperInterface::setSize()
	 */
	public function setSize($width = null, $height = null) 
	{
		if(null === $height && null !== $width) {
			// $width : $this->imageInfo['width'] = $height : $this->imageInfo['height']
			$height = $width * $this->imageInfo['height'] / $this->imageInfo['width'];
		}
		
		if(null === $width && null !== $height) {
			// $width : $this->imageInfo['width'] = $height : $this->imageInfo['height']
			$width = $height * $this->imageInfo['width'] / $this->imageInfo['height'];
		}
	
		$this->width = $width;
		$this->height = $height;
		
		return $this;
	}
	
	/**
	 * Calculate crop starting coords
	 * Type #1
	 * $this->width and $this->height 
	 * must be centered related to 
	 * $this->imageInfo['width'] and $this->imageInfo['height']
	 * 
	 * @return array
	 */
	public function calculateCropStartXY($type = self::CROPPER_TYPE_CENTER) 
	{
		switch ($type) {
		case self::CROPPER_TYPE_TOP:
			$width = ($this->imageInfo['width'] - $this->width) / 2;
			$width = $width < 0 ? 0 : $width;
			
			$height = 0;
			break;
		case self::CROPPER_TYPE_CENTER:
			$width = ($this->imageInfo['width'] - $this->width) / 2;
			$width = $width < 0 ? 0 : $width;
			
			$this->height = $this->getSquareMode() ? $this->width : $this->height;
			$height = ($this->imageInfo['height'] - $this->height) / 2;
			//$height = $height < 0 ? 0 : $height;
		default:
			break;
		}
		
		return array($width, $height);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Cropper\Cropper\CropperInterface::save()
	 */
	public function save($destImagePath) 
	{
        list($cropStartX, $cropStartY) = $this->calculateCropStartXY(); // Crop Start X & Y
		
        /*
        TODO: use imagecrop php function if available
        $this->sourceImageResource = imagecrop($this->sourceImageResource , array(
        		$cropStartX,
        		$cropStartY,
        		$this->width,
        		$this->height
        ));*/

    	// gif does not supports truecolor for resize
    	if (function_exists("ImageCreateTrueColor") && $this->getExtension() != 'gif') {
    		$image = ImageCreateTrueColor($this->width, $this->height);
    	} else {
    		$image = ImageCreate($this->width, $this->height);
    	}
    	
		imagecopy(
			$image, $this->sourceImageResource, 
			0, 0, $cropStartX, $cropStartY, 
			$this->width, $this->height
		);
		
		switch ($this->getExtension()) {
			case 'png':
				$croppedImage = @imagepng($image, $destImagePath, 0, null); // Corrupted image if NULL fourth parameter omitted!
				break;
		
			case 'jpg':
				$croppedImage = @imagejpeg($image, $destImagePath, 100);
				break;
		
			case 'gif':
				$croppedImage = @imagegif($image, $destImagePath, 100);
				break;
		}
		
		// Destruction of the images from memory
		@imagedestroy($this->sourceImageResource);
		@imagedestroy($image);
		
		return $croppedImage;
	}
}