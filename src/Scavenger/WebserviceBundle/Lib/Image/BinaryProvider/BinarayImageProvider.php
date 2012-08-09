<?php

namespace Scavenger\WebserviceBundle\Lib\Image\BinaryProvider;
 

abstract class BinarayImageProvider 
{
    protected $binaryImageData;
    protected $imageData;
    
            
    public function getBinaryImageData($rawImageData)
    {
        if (!$this->binaryImageData) {
            $this->binaryImageData = $this->convertImageData($rawImageData); 
        }
        
        return $this->binaryImageData;
    }
    
    protected abstract function convertImageData($rawImageData);
    
}
