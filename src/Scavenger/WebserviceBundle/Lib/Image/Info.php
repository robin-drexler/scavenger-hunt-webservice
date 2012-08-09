<?php

namespace Scavenger\WebserviceBundle\Lib\Image;
use Scavenger\WebserviceBundle\Lib\Image\BinaryProvider\BinarayImageProvider;


class Info 
{
    /**
     *
     * @var \finfo 
     */
    private $fileInfo;
    
    /**
     *
     * @var Scavenger\WebserviceBundle\Lib\Image\BinaryProvider\BinarayImageProvider 
     */
    private $binaryImageProvider;
    
    /**
     * Fileinfo determines which Info is being extracted. Yeah that's php style
     * @param \finfo $fileInfo 
     */
    public function __construct(\finfo $fileInfo, BinarayImageProvider $binaryImageProvider)
    {
        $this->fileInfo = $fileInfo;
        $this->binaryImageProvider = $binaryImageProvider;
    }
    
    
    public function get($imageData)
    {
        return $this->fileInfo->buffer($this->binaryImageProvider->getBinaryImageData($imageData));
    }
}