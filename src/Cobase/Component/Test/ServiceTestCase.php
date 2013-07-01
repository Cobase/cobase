<?php
namespace CoBase\Component\Test;

use Cobase\Component\Test\Fixture\FixtureFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

class ServiceTestCase extends ContainerTestCase
{
    /**
     * @var ServiceManager
     */
    private $serviceManager;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var SchemaTool
     */
    private $schemaTool;

    /**
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * @return FixtureFactory
     */
    public function getFixtureFactory()
    {
        if (!$this->fixtureFactory) {
            $this->setUpFixtures();
        }

        return $this->fixtureFactory;
    }

    protected function setUpFixtures()
    {
        $this->fixtureFactory = new FixtureFactory($this->getEntityManager());
        $this->fixtureFactory->setUpFixtures();
    }
    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if (!$this->em) {
            $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $this->schemaTool = $this->createSchemaTool($this->em);
        }

        return $this->em;
    }

    /**
     * @param EntityManager $em
     * @return SchemaTool
     */
    private function createSchemaTool(EntityManager $em)
    {
        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($em->getMetadataFactory()->getAllMetadata());

        return $schemaTool;
    }
}
