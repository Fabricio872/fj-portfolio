<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Psr\Cache\InvalidArgumentException;

class ImageOptimizer
{
    private readonly Imagine $imagine;

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
        return $photo->resize(new Box($width, (int) $height))->get('jpeg');
    }

    /**
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getMime(string $imageData)
    {
        $imageSize = getimagesizefromstring($imageData);
        if (empty($imageSize['mime'])) {
            throw new Exception("Cannot find MIME type");
        }
        return $imageSize['mime'];
    }
}
