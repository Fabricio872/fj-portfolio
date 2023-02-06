<?php

namespace App\Service;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SeleniumImage
{
    public function __construct(
        private ParameterBagInterface $bag
    )
    {
    }

    public function getImageFromUrl(string $url, string $fileName = null): string
    {
        $driver = RemoteWebDriver::create($this->bag->get('seleniumUrl'), DesiredCapabilities::firefox());

        $driver->get($url);
        $driver->wait(2);
        $data = $driver->takeScreenshot($fileName);
        $driver->quit();
        return $data;
    }
}