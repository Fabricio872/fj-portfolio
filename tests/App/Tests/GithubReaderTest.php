<?php

declare(strict_types=1);

namespace App\Tests;

use App\Service\GithubReader;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GithubReaderTest extends KernelTestCase
{
    public function testListRepositories(): void
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();
        /** @var GithubReader $githubReader */
        $githubReader = $container->get(GithubReader::class);
        $this->assertIsArray($githubReader->getRepositoriesArray());
    }

    public function testGetRepository()
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();
        /** @var GithubReader $githubReader */
        $githubReader = $container->get(GithubReader::class);
        $repo = $githubReader->getRepository(356_671_779); // Fabricio872/RegisterCommandBundle

        $this->assertEquals('Fabricio872/RegisterCommandBundle', $repo->getFullName());
    }

    public function testGetTag()
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();
        /** @var GithubReader $githubReader */
        $githubReader = $container->get(GithubReader::class);
        $tag = $githubReader->getTag('RegisterCommandBundle'); // Fabricio872/RegisterCommandBundle

        $this->assertIsArray($tag);
    }

    public function testGetCommit()
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();
        /** @var GithubReader $githubReader */
        $githubReader = $container->get(GithubReader::class);
        $commit = $githubReader->getCommit('RegisterCommandBundle', '28285cdb2b4f0fe68683c68e084dfc0f2d97667d'); // Fabricio872/RegisterCommandBundle

        $this->assertEquals('28285cdb2b4f0fe68683c68e084dfc0f2d97667d', $commit['sha']);
    }
}
