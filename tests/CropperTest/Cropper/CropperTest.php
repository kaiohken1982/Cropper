<?php 
namespace CropperTest\Cropper; 

use PHPUnit_Framework_TestCase;

use CropperTest\Bootstrap;

use Cropper\Cropper\Cropper;

class CropperTest 
	extends PHPUnit_Framework_TestCase
{
	protected $obj;
	
	protected $testImagePath;
	
	protected $testImageName = 'gatti.jpg';
	
	protected function setUp()
	{
        $serviceManager = Bootstrap::getServiceManager();
	    $thumbnailerService = $serviceManager->get('Thumbnailer');
		$this->testImagePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' 
		    . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
	    $this->obj = new Cropper($thumbnailerService);
		$this->obj->open($this->testImagePath . $this->testImageName);
	}
	
	/**
	 * @covers \Cropper\Cropper\Cropper::__construct()
	 * @covers \Cropper\Cropper\Cropper::getType()
	 * @covers \Cropper\Cropper\Cropper::setType()
	 */
	public function testGetDefaultTypeInConstruct() 
	{
		$type = $this->obj->getType();
		$this->assertEquals(Cropper::CROPPER_TYPE_CENTER, $type);
	}
}