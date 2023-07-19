<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\GithubRepo;
use App\Service\GithubReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsCommand(
    name: 'app:update-github-repos',
    description: 'Updates Github repositories',
)]
class UpdateGithubReposCommand extends Command
{
    public function __construct(
        private readonly GithubReader $githubReader,
        private readonly EntityManagerInterface $em,
        private readonly DenormalizerInterface $denormalizer,
        private readonly NormalizerInterface $normalizer
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        //            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        //            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->githubReader->getRepositories(true) as $githubRepo) {
            $githubRepoDb = $this->em->getRepository(GithubRepo::class)->find($githubRepo);
            if (empty($githubRepoDb)) {
                $this->em->persist($githubRepo);
                $output->writeln(sprintf('Added repo: %s', $githubRepo->getName()));
            } else {
                $this->em->persist($this->denormalizer->denormalize(
                    $this->normalizer->normalize($githubRepo),
                    GithubRepo::class,
                    context: [
                        AbstractNormalizer::OBJECT_TO_POPULATE => $githubRepoDb
                    ]
                ));
                $output->writeln(sprintf('Updated repo: %s', $githubRepo->getName()));
            }
        }
        $this->em->flush();

        return Command::SUCCESS;
    }
}
