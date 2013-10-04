<?php
namespace Cropper\Filter\File;

use Zend\Filter\AbstractFilter;

use Thumbnailer\Thumbnailer\Thumbnailer;
use Cropper\Cropper\Cropper;

class ImageCrop 
	extends AbstractFilter
{
	protected $options = array(
		'thumbnailer' => null
	);
	
	public function __construct($options)
	{
		$this->setOptions($options);
	}
	
	/**
	 * Get the thumbnailer given with the options
	 * @throws \Exception
	 * @return Thumbnailer\Thumbnailer\Thumbnailer
	 */
    protected function setThumbnailer(Thumbnailer $thumbnailer)
    {
    	if(!$thumbnailer instanceof Thumbnailer) {
    		throw new \Exception('The thumbnailer service given is not instance of Thumbnailer\Thumbnailer\Thumbnailer');
    	}
        $this->options['thumbnailer'] = $thumbnailer;
        
        return $this;
    }
    
    protected function getThumbnailer() 
    {
    	return $this->options['thumbnailer'];
    }
    
    /**
     * @param  string $value
     * @return string|mixed
     */
    public function filter($value)
    {
    	$isFile = false;
    	if(is_array($value) && isset($value['tmp_name'])) {
    		$filtered = $value['tmp_name'];
    		$isFile = true;
    	} else {
    		$filtered = $value;
    	}
    	
    	$basename = basename($filtered);
    	$dirname = dirname($filtered);
    	
    	$cropper = new Cropper($this->getThumbnailer());
        $cropper->open($filtered);
        $cropper->setSquareMode(true); 
        $cropper->save($filtered);
        
        return $value;
    }
}