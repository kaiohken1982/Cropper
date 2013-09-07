Image Cropper Module
====================

An image cropper service module for Zend Framework 2.


### Install with Composer
 ```
{
  "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/kaiohken1982/Cropper.git"
        }
    ],
    "require": {
        ......,
        "razor/cropper" : "dev-master"
    }
}
 ```

### How to use

In a controller

 ```
 		$cropper = $this->getServiceLocator()->get('Cropper');
		$cropper->open('/path/to/image.jpg');
		
		// Type of the cut, on top image (2) or centered (1), default 1
		// $cropper->setType(1);
		
		// this will cut a 200x200 square
		$cropper->setSize(200, 200);
		
		// this will cut a square in the source image, where side equal source image width
		//$cropper->setSquareMode(true); 
		
		$cropper->save('/path/to/image_cropped.jpg');
 ```

### TODO

 ```
Tests

 ```