<?php

namespace App\Tests;

use App\Service\SeleniumImage;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SeleniumImageTest extends KernelTestCase
{
    public function testImageFromCorrectUrlGetsData(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $parameterBag = static::getContainer()->get('parameter_bag');
        $seleniumImage = new SeleniumImage($parameterBag);

        $data = $seleniumImage->getImageFromUrl('https://www.google.com/');
        $imagePath = tempnam(sys_get_temp_dir(), 'test-image-');
        file_put_contents($imagePath, $data);

        $this->assertFileExists($imagePath);
    }

    public function testImageFromCorrectUrlGetsFile(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $parameterBag = static::getContainer()->get('parameter_bag');
        $seleniumImage = new SeleniumImage($parameterBag);

        $imagePath = tempnam(sys_get_temp_dir(), 'test-image-');
        $seleniumImage->getImageFromUrl('https://www.google.com/', $imagePath);

        $this->assertFileExists($imagePath);
    }
}
