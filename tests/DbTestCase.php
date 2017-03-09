<?php

namespace Tests;

use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;

class DbTestCase extends BaseWebTestCase
{
    /**
     * List of fixtures to load.
     *
     * @var array
     */
    protected $fixtureList = [
        // add list of fixture classes
    ];

    /**
     * @var \Doctrine\Common\DataFixtures\ReferenceRepository
     */
    protected $fixtures;

    public function setUp()
    {
        parent::setUp();

        $em = $this->getContainer()->get('doctrine')->getManager();
        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();
        if (!empty($metadatas)) {
            $schemaTool->createSchema($metadatas);
        }
        $this->postFixtureSetup();

        // @todo loading the user table can be very slow – can we not drop it every time
        $this->fixtures = $this->loadFixtures($this->fixtureList)
            ->getReferenceRepository();
    }

    public function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}