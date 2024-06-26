<?php

namespace Pushword\PageScanner\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class PageScannerCommandTest extends KernelTestCase
{
    public function testPageScannerCommand(): void
    {
        $kernel = self::createKernel();
        $application = new Application($kernel);

        $command = $application->find('pushword:page-scanner:scan');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['localhost.dev']);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        self::assertTrue(str_contains($output, 'done...'));
    }
}
