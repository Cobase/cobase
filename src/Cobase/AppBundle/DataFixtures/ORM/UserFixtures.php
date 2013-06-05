<?php

namespace Cobase\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Cobase\UserBundle\Entity\User;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setUsername('dev1');
        $user1->setUsernameCanonical('dev1');
        $user1->setPlainPassword('dev1');
        $user1->setName('Morgan Freeman');
        $user1->setGravatar('dev1@developer.me');
        $user1->setEmail('dev1@developer.me');
        $user1->setEmailCanonical('dev1@developer.me');
        $user1->setEnabled(true);
        $user1->setLocked(false);
        $user1->setExpired(false);
        $user1->setCredentialsExpired(false);
        
        $user2 = new User();
        $user2->setUsername('dev2');
        $user2->setUsernameCanonical('dev2');
        $user2->setPlainPassword('dev2');
        $user2->setName('Ray Charles');
        $user2->setGravatar('dev2@developer.me');
        $user2->setEmail('dev2@developer.me');
        $user2->setEmailCanonical('dev2@developer.me');
        $user2->setEnabled(true);
        $user2->setLocked(false);
        $user2->setExpired(false);
        $user2->setCredentialsExpired(false);
        
        $user3 = new User();
        $user3->setUsername('dev3');
        $user3->setUsernameCanonical('dev3');
        $user3->setPlainPassword('dev3');
        $user3->setName('Dave Gahan');
        $user3->setGravatar('dev3@developer.me');
        $user3->setEmail('dev3@developer.me');
        $user3->setEmailCanonical('dev3@developer.me');
        $user3->setEnabled(true);
        $user3->setLocked(false);
        $user3->setExpired(false);
        $user3->setCredentialsExpired(false);
        
        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        
        $manager->flush();
        
        $this->addReference('user-1', $user1);
        $this->addReference('user-2', $user2);
        $this->addReference('user-3', $user3);
    }
    
    public function getOrder()
    {
        return 1;
    }
}
