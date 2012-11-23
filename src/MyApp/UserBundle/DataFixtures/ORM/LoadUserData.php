<?php
// src/MyApp/UserBundle/DataFixtures/ORM/LoadUserData.php

namespace MyApp\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyApp\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {
        $password = "helloworld";

        $obj = new User();
        $obj->setUsername("siciarek");
        $obj->setFirstName("Jacek");
        $obj->setLastName("Siciarek");
        $obj->setDescription("My App Developer");
        $obj->setEmail("siciarek@gmail.com");
        $obj->setPlainPassword($password);
        $obj->setEnabled(true);
        $obj->addRole("ROLE_SUPER_ADMIN");
        $om->persist($obj);
        $om->flush();
        
        $this->addReference("user-siciarek", $obj);
    }
 }