<?php

namespace Pushword\PageScanner;

use Pushword\Core\Entity\Page;
use Pushword\PageScanner\Scanner\LinkedDocsScanner;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LinkedDocsScannerTest extends KernelTestCase
{
    public function testLinkedDocsScanner()
    {
        self::bootKernel();
        $linkedDocsScanner = new LinkedDocsScanner(
            self::$kernel->getContainer()->get('doctrine.orm.default_entity_manager'),
            [],
            __DIR__.'/../../skeleton/public',
            self::$kernel->getContainer()->get('translator')
        );

        $errors = $linkedDocsScanner->scan($this->getPage(), file_get_contents(__DIR__.'/data/page.html'));

        $knowedErrors = [
            '<code>https://localhost.dev/feed.xml</code> unreacheable',
            '<code>https://localhost.dev/</code> unreacheable',
            '<code>#install</code> target not found',
        ];

        foreach ($knowedErrors as $error) {
            $this->assertContains($error, $errors);
        }
    }

    public function getPage(): Page
    {
        $page = (new Page())
            ->setH1('Welcome : this is your first page')
            ->setSlug('homepage')
            ->setLocale('en')
            ->setCreatedAt(new \DateTime('2 days ago'))
            ->setMainContent('...'); // \Safe\file_get_contents( __DIR__.'/../../skeleton/src/DataFixtures/WelcomePage.md')
        $page->setCustomProperty('pageScanLinksToIgnore', ['https://example2.tld/*']);

        return $page;
    }
}
