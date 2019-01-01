<?php

namespace App\Command;

use App\Entity\Article;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestCommand extends Command
{
    protected static $defaultName = 'test';
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine, $name = null)
    {
        parent::__construct($name);
        $this->doctrine = $doctrine;
    }

    protected function configure()
    {
        $this
          ->addArgument('id')
          ->setDescription('Creates a new user.')
          ->setHelp('This command allows you to create a user...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $output->writeln(
          $this->doctrine->getRepository(Article::class)->find(
            $input->getArgument('id')
          )->getText()
        );
        $io->title($this->doctrine->getRepository(Article::class)->find(
          $input->getArgument('id')
        )->getText());
    }
}
