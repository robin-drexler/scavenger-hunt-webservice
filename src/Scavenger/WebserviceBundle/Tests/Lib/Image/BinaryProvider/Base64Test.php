<?php

namespace Scavenger\WebserviceBundle\Test\Lib\Image\BinaryProvider;
use Scavenger\WebserviceBundle\Lib\Image\BinaryProvider\Base64;

class Base64Test extends \PHPUnit_Framework_TestCase
{
    
    const SOME_BASE64_IMAGE = 'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9wDBxA2NbDWwrsAAALOSURBVDjLVZNPiJVlFMZ/57zvd+/ENWbmate8II6DNlhNJFowGki5KcUkpEUghLVpZPpjIQUtahMRQVBITBiBbXJplDJCmzYmUYKN2WJQQy0bFWbmzm3u/e59v3NazGjMA2dzeJ5zHnjOEZbwO/AQcL3GGofdqgyqssIcTS3+8YLzA2f4jmGWQQCmgI3A5R7el4ynNTAjgYSAG+qJkhf0WBdx490NiR/vDrizeSoyIcJKgSSRJIIBOAgFwQ01oyTGvea89wB8c9fBsfUc2XZFnnS8UKVAMQH3JYI5iqHuBNAMYtXwnVnoTko2zlBwvjw7GtZVkNyxJJAF7GQJzmlGNd/Ni9or96uKqkiQAFnVrt33kT8aCey1Ms0ju+zW2KlsdYUQT9eGXh574djDdFtbkGA0Bg4vvLR6VHt8b4ygEUWo5s+zWbKjHNcSfXkZn9gfhlel4qutb/y2Di8q4DmIgPSy0PrCXx351n2x4w6py9uK0Y/j5Rz5YH9x42TtiWkkjiDZGoh1JNSRuBKt7gHO3hED7sZg9C7iZRCHMzuZvfnn1QbW7UejIMGWqApewogoiOBWgCVQa/EXBYJAMNLlfVfX07jxK63bFdozKxbrVq3eOXEaZav7YrRFgk6HSbU2562NeLF4F9rLjmzguRNMX5mgcQnmLv3L1E+H/njlnTeXrGMJ77SQ1hynRD+kbsbxbBULWgIUgOBd/ibnQtHkngM19h19jEfcFsXdNsX8LD/XdrFdADgcX6c/PRt7SdqDE3AB3BGfQRs7eKoUUEt4p401Z3EX1lpiOgLwcfqUt2I9tYvHtc87oYx4xLsJ/3oDQ6FA8jZFvkDRnCPrzvPM4AGmAQIAB4HP7Ac2lXNvhm3WcvGEbExUPlnLUHOOND9DnL3JhfnbjGwa5dyyb1yO12BsfA+5PDg+mG/Z3gckLqbE95sP8cvk5zB88H/2f9a3SiVPVtlCAAAAAElFTkSuQmCC';
    const SOME_MIME = 'image/png; charset=binary';
    
    private $converter;
    
    public function setUp()
    {
        $this->converter = new Base64();
    }
    
    /**
     * @test 
     */
    public function itShouldConvertABase64ImageIntoBinary()
    {
        $imageData64 = self::SOME_BASE64_IMAGE;
        
        //Using Mime Type, 'cause binary comparison is some kind of akward 
        $expectedMime = self::SOME_MIME;
        $f = new \finfo(FILEINFO_MIME);
        
        $this->assertThat($f->buffer($this->converter->getBinaryImageData($imageData64), FILEINFO_MIME), $this->equalTo($expectedMime));
    }
}

?>
