<?php

namespace Cobase\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Cobase\AppBundle\Entity\Post;
use Cobase\AppBundle\Entity\Group;

class PostsFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $post1 = new Post();
        $post1->setUser($manager->merge($this->getReference('user-1')));
        $post1->setContent('According to Google...');
        $post1->setGroup($manager->merge($this->getReference('group-1')));
        $manager->persist($post1);

        $post2 = new Post();
        $post2->setUser($manager->merge($this->getReference('user-2')));
        $post2->setContent('New PHP version released');
        $post2->setGroup($manager->merge($this->getReference('group-2')));
        $manager->persist($post2);

        $post3 = new Post();
        $post3->setUser($manager->merge($this->getReference('user-3')));
        $post3->setContent('Here are the latest news!');
        $post3->setGroup($manager->merge($this->getReference('group-2')));
        $manager->persist($post3);

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}