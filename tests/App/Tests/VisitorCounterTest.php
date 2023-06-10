<?php

declare(strict_types=1);

namespace App\Tests\App\Tests;

use App\Entity\Visitor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VisitorCounterTest extends WebTestCase
{
    private EntityManagerInterface $em;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = self::getContainer()->get(EntityManagerInterface::class);

        $metaData = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropSchema($metaData);
        $schemaTool->createSchema($metaData);
    }

    public function testIfVisitorIsLoggedOnMainPage()
    {
        $this->client->request('GET', '/');
        $visitors = $this->em->getRepository(Visitor::class)->findAll();

        $this->assertResponseIsSuccessful();
        $this->assertCount(1, $visitors);
    }
}
