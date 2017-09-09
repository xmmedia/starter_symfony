<?php

namespace Tests;

use AppBundle\DataFixtures\ORM\LoadDefaultFixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class WebTestCase extends BaseWebTestCase
{
    /**
     * List of fixtures to load.
     *
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

    /**
     * Login the user based on the fixture reference.
     *
     * @param string $userRef
     */
    protected function logIn($userRef)
    {
        $user = $this->fixtures->getReference($userRef);

        $this->loginAs($user, 'main');
    }

    /**
     * Escapes a string for use within the DomCrawler selector strings.
     *
     * @param string $str
     *
     * @return string
     */
    protected function e($str)
    {
        return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'utf-8');
    }
}