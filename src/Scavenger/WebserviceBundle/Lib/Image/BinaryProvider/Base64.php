<?php

namespace Scavenger\WebserviceBundle\Lib\Image\BinaryProvider;

use Scavenger\WebserviceBundle\Lib\Image\BinaryProvider\BinarayImageProvider;
 
class Base64 extends BinarayImageProvider
{
    
    protected function convertImageData($rawImageData) 
    {
        return base64_decode($rawImageData);
    }
}

?>
