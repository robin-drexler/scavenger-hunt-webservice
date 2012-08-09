<?php

namespace Scavenger\WebserviceBundle\Lib\Image\Response;


class Base64ImageInfo 
{
    private $fileInfo;
    
    /**
     *
     * @param \finfo $fileInfo 
     */
    public function __construct(\finfo $fileInfo)
    {
        $this->fileInfo = $fileInfo;
    }
    
    
    public function getMimeType()
    {
        
    }
}