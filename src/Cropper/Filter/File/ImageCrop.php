<?php
namespace Cropper\Filter\File;

use Zend\Filter\AbstractFilter;

use Thumbnailer\Thumbnailer\Thumbnailer;
use Cropper\Cropper\Cropper;

class ImageCrop 
	extends AbstractFilter
{
	protected $options = array(
		'thumbnailer' => null,
		'keepOriginal' => false
	);
	
	public function __construct($options)
	{
		$this->setOptions($options);
	}
	
	/**
	 * Set the thumbnailer given with the options
	 * @throws \Exception
	 * @return Cropper\Filter\File\ImageCrop
	 */
    protected function setThumbnailer(Thumbnailer $thumbnailer)
    {
    	if(!$thumbnailer instanceof Thumbnailer) {
    		throw new \Exception('The thumbnailer service given is not instance of Thumbnailer\Thumbnailer\Thumbnailer');
    	}
        $this->options['thumbnailer'] = $thumbnailer;
        
        return $this;
    }
    
    /**
     * Get thumbnailer service
     * @return Thumbnailer\Thumbnailer\Thumbnailer;
     */
    protected function getThumbnailer() 
    {
    	return $this->options['thumbnailer'];
    }
    
    protected function getKeepOriginal() 
    {
    	return $this->options['keepOriginal'];
    }
    
    /**
     * @param  string $value
     * @return string|mixed
     */
    public function filter($value)
    {
    	$isFile = false;
    	if(is_array($value) && isset($value['tmp_name'])) {
    		$image = $value['tmp_name'];
    		$isFile = true;
    	} else {
    		$image = $value;
    	}
    	
    	
    	
    	$cropper = new Cropper($this->getThumbnailer());
        $cropper->open($image);
        $cropper->setSquareMode(true); 
        
        if(!$this->getKeepOriginal()) {
        	$cropper->save($image);
        } else {
	    	$basename = basename($image);
	    	$dirname = dirname($image);
	    	$image = $dirname . DIRECTORY_SEPARATOR . 'cropped_' . $basename;
        	$cropper->save($image);
        }
        
        return array('name' => $image);
    }
}
