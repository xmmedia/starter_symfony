<?php

namespace Tests;

use AppBundle\DataFixtures\ORM\LoadDefaultFixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

class WebTestCase extends BaseWebTestCase
{
    /**
     * List of fixtures to load.
     * @var array
     */
    protected $fixtureList = [
        LoadDefaultFixtures::class,
    ];

    /**
     * @var \Doctrine\Common\DataFixtures\ReferenceRepository
     */
    protected $fixtures;

    public function setUp()
    {
        parent::setUp();

        $this->fixtures = $this->loadFixtures($this->fixtureList)
            ->getReferenceRepository();
    }

    protected function logIn($userRef = 'user-regular')
    {
        $user = $this->fixtures->getReference($userRef);

        $this->loginAs($user, 'main');
    }

    protected function assertPathInfoMatches(Client $client, $regExp)
    {
        $this->assertRegExp($regExp, $client->getRequest()->getPathInfo());
    }

    protected function findAdminPageTitle(Crawler $crawler, $title)
    {
        return $crawler->filter(
            '.header-page_title:contains("'.$title.'")'
        );
    }
}