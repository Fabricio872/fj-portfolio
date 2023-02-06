<?php

namespace App\Service;

use Exception;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageOptimizer
{
    private $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }


    public function resize(?string $image, int $width, int $height = -1): string
    {
        if (! $image) {
            throw new Exception("No image data provided");
        }
        [$iwidth, $iheight] = (array) getimagesizefromstring($image);
        $ratio = $iwidth / $iheight;
        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }

        $photo = $this->imagine->load($image);
        return $photo->resize(new Box($width, (int) $height))->get('png');
    }
}