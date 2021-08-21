<?php

namespace Fundamental\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RandNumCommand extends Command
{
    protected static $defaultName = 'fundamental:rand-num';
    protected static $defaultDescription = 'Generate a unique random number with sodium ext';

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Message to be randomized and crypted')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $crypted = md5($this->getHashingMessage($arg1));

        $io->success("$crypted.");

        return 0;
    }

    protected function getHashingMessage(string $message): string
    {
        return sodium_crypto_generichash($message); 
    }
}
