<?php
// src/MyApp/UserBundle/DataFixtures/ORM/LoadUserData.php

namespace MyApp\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyApp\UserBundle\Entity\Group;

class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface
{

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $obj = new Group("owners");
        $obj->setDescription("Account owners");
        $manager->persist($obj);
        $manager->flush();
        $this->addReference("account-owners", $obj);

        $obj = new Group("users");
        $obj->setDescription("Regular users");
        $manager->persist($obj);
        $manager->flush();
        $this->addReference("regular-users", $obj);
    }
}