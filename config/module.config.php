<?php
namespace Cropper;

return array(
	'service_manager' => array(
		'factories' => array(
			'Cropper\Cropper\Cropper' => new Service\CropperFactory()
		),
		'aliases' => array(
			'Cropper' => 'Cropper\Cropper\Cropper'
		)
	)
);