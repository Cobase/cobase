<?php

namespace Cobase\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Cobase\AppBundle\Entity\Subscription;
use Cobase\AppBundle\Entity\Group;

class SubscriptionFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $subscription1 = new Subscription();
        $subscription1->setUser($manager->merge($this->getReference('user-1')));
        $subscription1->setGroup($manager->merge($this->getReference('group-1')));
        $manager->persist($subscription1);

        $subscription2 = new Subscription();
        $subscription2->setUser($manager->merge($this->getReference('user-1')));
        $subscription2->setGroup($manager->merge($this->getReference('group-2')));
        $manager->persist($subscription2);
        
        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}
