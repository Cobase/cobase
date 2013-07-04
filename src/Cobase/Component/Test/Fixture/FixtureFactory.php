<?php
namespace Cobase\Component\Test\Fixture;

use Doctrine\ORM\EntityManager;
use Xi\Fixtures\FixtureFactory as BaseFixtureFactory;

class FixtureFactory extends BaseFixtureFactory
{
    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);

        $this->setEntityNamespace('CoBase');
        $this->persistOnGet(true);
    }

    /**
     * @return FixtureFactory
     */
    public function setUpFixtures()
    {
        return $this;
    }
}
