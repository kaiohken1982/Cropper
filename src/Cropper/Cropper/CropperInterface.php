<?php 
namespace Cropper\Cropper;

interface CropperInterface 
{
	/**
	 * Configure object params
	 * 
	 * @param array $config
     * @return \Watermarker\Watermarker\Watermarker
	 */
	function parseConfig($config);
	
	/**
	 * Open an image to be cropped
	 * 
	 * @param string $sourceImagePath
	 * @return \Cropper\Cropper\CropperInterface
	 */
	function open($sourceImagePath);
	
	/**
	 * Size of the cropped image
	 * 
	 * @param int $width
	 * @param int $height
	 */
	function setSize($width, $height);
	
	/**
	 * Crop the opened image
	 * 
	 * @param string $destImagePath
	 * @return bool
	 */
	function save($destImagePath);
}