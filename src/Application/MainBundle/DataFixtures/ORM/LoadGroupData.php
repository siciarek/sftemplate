<?php

namespace Application\MainBundle\DataFixtures\ORM;

use Application\MainBundle\ApplicationMainBundle;
use Application\MainBundle\Security\Roles\ClientRole;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadGroupData extends BaseFixture
{
    protected $order = 1;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {
        $gm = $this->container->get('fos_user.group_manager');

        foreach ($this->getData('Group') as $g) {
            $group = $gm->createGroup($g['name']);

            foreach ($g['roles'] as $role) {
                $group->addRole($role);
            }

            $gm->updateGroup($group);

            $this->setReference('group-' . $g['name'], $group);
        }
    }
}