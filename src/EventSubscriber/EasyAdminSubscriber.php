<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\WebProject;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Cache\CacheInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly CacheInterface $cache
    ) {
    }

    public static function getSubscribedEvents():array
    {
        return [
            AfterEntityUpdatedEvent::class => ['dumpWebProjectImage'],
        ];
    }

    public function dumpWebProjectImage(AfterEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (! ($entity instanceof WebProject)) {
            return;
        }
        $this->cache->delete(sprintf('web-project-image-%s', $entity->getId()));
        return;
    }
}
