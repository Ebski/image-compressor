## Image Compressor
This library is handy if you need to compress an image to the format of webp. 
It uses cwebp to compress images into the webp format so make sure to have cwebp available on your server

Other compression formats can be added later if there is a need / want for it.

## Installation
Using composer:
```
composer require ebski/image-compressor
```

to install cwebp on a linux machine
```
sudo apt-get update
sudo apt-get install webp
```

## Usage
Instantiate the WebpCompressor class and call the function compressImage:
```php
<?php

use Ebski\ImageCompressor\Compressors\WebpCompressor;
use Ebski\ImageCompressor\Quality;

public function example()
{
    $compressor = new WebpCompressor();
    $compressedImagePath = $compressor->compressImage('path_to_original_image', Quality::MEDIUM);
}
```

cleanup of compressed images happens automatically on destroy