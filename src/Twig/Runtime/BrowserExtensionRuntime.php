<?php

namespace App\Twig\Runtime;

use foroco\BrowserDetection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\RuntimeExtensionInterface;

class BrowserExtensionRuntime implements RuntimeExtensionInterface
{
    private $browserDetection;
    private Request $request;

    public function __construct(
        RequestStack $requestStack
    )
    {
        $this->browserDetection = new BrowserDetection();
        $this->request = $requestStack->getCurrentRequest();
    }

    public function getBrowser(): array
    {
        return $this->browserDetection->getBrowser($this->request->server->get('HTTP_USER_AGENT'));
    }
}
