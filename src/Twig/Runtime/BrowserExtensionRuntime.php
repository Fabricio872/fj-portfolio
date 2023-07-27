<?php

declare(strict_types=1);

namespace App\Twig\Runtime;

use App\Exception\InvalidConfigParameterTypeException;
use foroco\BrowserDetection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\RuntimeExtensionInterface;

class BrowserExtensionRuntime implements RuntimeExtensionInterface
{
    private readonly BrowserDetection $browserDetection;

    private readonly Request $request;

    public function __construct(
        RequestStack $requestStack
    ) {
        $this->browserDetection = new BrowserDetection();
        $request = $requestStack->getCurrentRequest();
        if (! $request) {
            throw new InvalidConfigParameterTypeException('request', Request::class, $request);
        }
        $this->request = $request;
    }

    /**
     * @return array<string, string>
     */
    public function getBrowser(): array
    {
        return $this->browserDetection->getBrowser($this->request->server->get('HTTP_USER_AGENT'));
    }
}
