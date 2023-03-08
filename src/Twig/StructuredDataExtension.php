<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\PrintedProject;
use App\Entity\WebProject;
use Doctrine\ORM\EntityManagerInterface;
use Spatie\SchemaOrg\Graph;
use Spatie\SchemaOrg\LocalBusiness;
use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\WebPage;
use Spatie\SchemaOrg\WebSite;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StructuredDataExtension extends AbstractExtension
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly RouterInterface $router,
        private readonly EntityManagerInterface $em
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('structuredData', $this->getData(...)),
        ];
    }

    public function getData(): string
    {
        $graph = new Graph();
        $website = new WebSite();
        $webProjects = $this->em->getRepository(WebProject::class)->findAll();
        $printedProjects = $this->em->getRepository(PrintedProject::class)->findAll();

        $website
            ->url($this->router->generate('app.index', referenceType: UrlGeneratorInterface::ABSOLUTE_URL))
            ->name($this->trans('meta.sd.name'))
            ->image(
                array_merge(
                    array_map(fn ($webProject) => Schema::imageObject()->url(
                        $this->router->generate(
                            'image.web-project',
                            ['id' => $webProject->getId()],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        )
                    ), $webProjects),
                    array_map(fn ($printedProject) => Schema::imageObject()->url($this->router->generate('image.uploaded', [
                        'imageName' => $printedProject->getImagePath(),
                        'size' => 'original'
                    ], UrlGeneratorInterface::ABSOLUTE_URL)), $printedProjects)
                )
            )
            ->description($this->trans('meta.sd.description'));
        $graph->add($website);
        $webpage = new WebPage();

        $webpage
            ->name($this->trans('meta.sd.name'))
            ->description($this->trans('meta.sd.description'))
            ->url($this->router->generate('app.index', referenceType: UrlGeneratorInterface::ABSOLUTE_URL))
            ->image(
                Schema::imageObject()
                    ->url($this->router->generate(
                        'image.asset',
                        ['imageName' => 'profile_picture.jpg', 'size' => 'medium'],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ))
            )
            ->potentialAction([
                Schema::readAction()->target(
                    Schema::entryPoint()->url($this->router->generate('app.index', referenceType: UrlGeneratorInterface::ABSOLUTE_URL))
                ),
            ]);

        $graph->add($webpage);

        $localBusiness = new LocalBusiness();

        $localBusiness
            ->name($this->trans('meta.sd.name'))
            ->url($this->router->generate('app.index', referenceType: UrlGeneratorInterface::ABSOLUTE_URL))
            ->logo($this->trans('meta.sd.logo'))
            ->priceRange('$')
            ->image(
                Schema::imageObject()
                    ->url($this->router->generate(
                        'image.asset',
                        ['imageName' => 'profile_picture.jpg', 'size' => 'medium'],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ))
            )
            ->founder(
                Schema::person()
                    ->name($this->trans('meta.sd.author'))
            )
            ->description($this->trans('meta.sd.description'))
            ->address(
                Schema::postalAddress()
                    ->addressLocality($this->trans('meta.sd.city'))
                    ->addressCountry($this->trans('meta.sd.country'))
            )
            ->email($this->trans('contact.email'));

        $graph->add($localBusiness);

        return $graph->toScript();
    }

    private function trans(string $trans): string
    {
        return $this->translator->trans($trans);
    }
}
