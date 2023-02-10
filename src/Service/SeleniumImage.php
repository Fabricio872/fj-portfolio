<?php

declare(strict_types=1);

namespace App\Service;

use Facebook\WebDriver\Exception\UnknownErrorException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Lock\LockFactory;

class SeleniumImage
{
    public function __construct(
        private readonly ParameterBagInterface $bag,
        private readonly LockFactory $lockFactory
    ) {
    }

    public function getImageFromUrl(string $url, string $fileName = null): string
    {
        $driver = null;
        $lock = $this->lockFactory->createLock('selenium-web-image-creation');
        $lock->acquire(true);
        try {
            $selenuimUrl = $this->bag->get('seleniumUrl');
            if (! is_string($selenuimUrl)) {
                throw new ParameterNotFoundException('seleniumUrl');
            }
            $driver = RemoteWebDriver::create($selenuimUrl, DesiredCapabilities::firefox());

            $driver->get($url);
            sleep(5);
            $data = $driver->takeScreenshot($fileName);
            $driver->quit();
            $driver->close();
        } catch (UnknownErrorException $exception) {
            if (! $driver instanceof RemoteWebDriver) {
                throw $exception;
            }
            $driver->quit();
            $driver->close();
            $noImage = $this->bag->get('noImage');
            if (! is_string($noImage)) {
                throw new ParameterNotFoundException('noImage');
            }
            $data = (string) file_get_contents($noImage);
        }

        $lock->release();

        return $data;
    }
}
