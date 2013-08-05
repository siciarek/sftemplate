<?php

namespace Application\MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;


class LoadUserData extends BaseFixture
{
    protected $order = 2;
    public $count = 0;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {
        $um = $this->container->get('fos_user.user_manager');

        foreach ($this->getData('User') as $u) {

            $user = $um->createUser();
            $user->setUsername($u['username']);
            $user->setEmail($u['email']);
            $user->setPlainPassword($u['password']);
            $user->setFirstname($u['firstname']);
            $user->setLastname($u['lastname']);
            $user->setEnabled($u['enabled']);
            $user->setSuperAdmin($u['superadmin']);

            foreach($u['roles'] as $role) {
                $user->addRole($role);
            }

            foreach($u['groups'] as $g) {
                $gname = 'group-' . $g;
                $user->addGroup($this->getReference($gname));
            }

            $um->updateUser($user);

            $this->setReference('user-' . $u['username'], $user);
        }

        $om->flush();
    }
}