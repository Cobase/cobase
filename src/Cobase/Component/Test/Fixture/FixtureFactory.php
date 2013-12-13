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
        $this->define('AppBundle\Entity\Group')
            ->sequence('title', 'title_%d')
            ->sequence('description', 'my-description-%d')
            ->field('isPublic', true)
            ->sequence('shortUrl', 'my-short-url-%d')
            ->sequence('slug', 'my-slug-%d');

        $this->define('AppBundle\Entity\Post')
            ->sequence('content', 'content-%d')
            ->field('created', new \DateTime())
            ->field('updated', new \DateTime());

        $this->define('AppBundle\Entity\PostEvent')
            ->reference('post', 'AppBundle\Entity\Post');

        return $this;
    }
}
