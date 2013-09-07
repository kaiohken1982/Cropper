<?php 
namespace Cropper\Service;

use Cropper\Cropper\Cropper;
use Zend\ServiceManager\ServiceLocatorInterface,
	Zend\ServiceManager\FactoryInterface;

class CropperFactory
	implements FactoryInterface
{
    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \Cropper\Cropper\Cropper
     */
    public function createService(ServiceLocatorInterface $sl)
    {
		$config = $sl->get('Configuration');
        $cropperConfig = isset($config['cropper']) ? $config['cropper'] : array();
        $cropper = new Cropper($sl->get('Thumbnailer'));
        $cropper->parseConfig($cropperConfig);
		
		return $cropper;
    }
}