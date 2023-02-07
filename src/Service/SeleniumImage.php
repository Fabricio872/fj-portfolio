<?php

namespace App\Service;

use Facebook\WebDriver\Exception\UnknownErrorException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Lock\LockFactory;

class SeleniumImage
{
    public function __construct(
        private ParameterBagInterface $bag,
        private LockFactory           $lockFactory
    )
    {
    }

    public function getImageFromUrl(string $url, string $fileName = null): string
    {
        $lock = $this->lockFactory->createLock('selenium-web-image-creation');
        $lock->acquire(true);
        try {
            $driver = RemoteWebDriver::create($this->bag->get('seleniumUrl'), DesiredCapabilities::firefox());

            $driver->get($url);
            sleep(5);
            $data = $driver->takeScreenshot($fileName);
            $driver->quit();
            $driver->close();
        } catch (UnknownErrorException $exception) {
            $driver->quit();
            $driver->close();
            $data = file_get_contents($this->bag->get('noImage'));
        }

        $lock->release();

        return $data;
    }
}
