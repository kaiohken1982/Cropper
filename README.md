Image Cropper Module
====================

[![Build Status](https://travis-ci.org/kaiohken1982/Cropper.png?branch=master)](https://travis-ci.org/kaiohken1982/Cropper)
[![Coverage Status](https://coveralls.io/repos/kaiohken1982/Cropper/badge.png?branch=master)](https://coveralls.io/r/kaiohken1982/Cropper?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/52f5629dec1375917400008a/badge.png)](https://www.versioneye.com/user/projects/52f5629dec1375917400008a)
[![Latest Stable Version](https://poser.pugx.org/razor/cropper/v/stable.png)](https://packagist.org/packages/razor/cropper) 
[![Total Downloads](https://poser.pugx.org/razor/cropper/downloads.png)](https://packagist.org/packages/razor/cropper) 
[![Latest Unstable Version](https://poser.pugx.org/razor/cropper/v/unstable.png)](https://packagist.org/packages/razor/cropper) 
[![License](https://poser.pugx.org/razor/cropper/license.png)](https://packagist.org/packages/razor/cropper)

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

### Run unit test
 
Please note you must be in the module root.

```
curl -s http://getcomposer.org/installer | php
php composer.phar install
./vendor/bin/phpunit 
```

If you have xdebug enabled and you want to see code coverage 
run the command below, it'll create html files in 
Watermarker\test\data\coverage

```
./vendor/bin/phpunit --coverage-html data/coverage
```
