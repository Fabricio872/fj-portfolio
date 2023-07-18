<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Visitor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener]
final readonly class VisitorCounter
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        if (
            str_starts_with((string) $event->getRequest()->attributes->get('_route'), '_') ||
            empty($event->getRequest()->attributes->get('_route'))
        ) {
            return;
        }

        $visitor = new Visitor();
        $visitor
            ->setRoute((string) $event->getRequest()->attributes->get('_route'))
            ->setLocale((string) $event->getRequest()->attributes->get('_locale'))
            ->setParams((array) $event->getRequest()->attributes->get('_route_params'));

        $this->em->persist($visitor);
        $this->em->flush();
    }
}
