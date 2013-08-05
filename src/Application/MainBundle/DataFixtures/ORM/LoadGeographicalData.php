<?php

namespace Application\MainBundle\DataFixtures\ORM;

use Application\MainBundle\ApplicationMainBundle;
use Application\MainBundle\Entity\AdministrativeDivision;
use Application\MainBundle\Entity\Country;
use Application\MainBundle\Entity\Place;
use Application\MainBundle\Entity\Currency;
use Application\MainBundle\Security\Roles\ClientRole;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Yaml\Yaml;


class LoadGeographicalData extends BaseFixture
{
    protected $order = 3;
    protected $test = true;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {

        return;

        $user = $this->getReference('user-system');

        // Here, 'public' is the name of the firewall in your security.yml
        $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
        $this->container->get('security.context')->setToken($token);

        foreach ($this->getData('Country') as $c) {
            $obj = new Country();
            $obj->setCode($c['code']);
            $obj->setName($c['name']);
            $obj->setLangCode($c['lang_code']);

            $om->persist($obj);
            $this->setReference('country-' . $c['code'], $obj);
        }

        foreach ($this->getData('AdministrativeDivision') as $d) {
            $obj = new AdministrativeDivision();
            $obj->setName($d['name']);
            $obj->setCountry($this->getReference('country-' . $d['country_code']));
            $om->persist($obj);
            $this->setReference('administrative-division-' . $d['name'], $obj);
        }

        foreach ($this->getData('Place') as $p) {

            if($this->test === true and $p['ad'] !== 'pomorskie') continue;

            $obj = new Place();
            $obj->setName($p['name']);
            $obj->setInfo($p['info']);
            $obj->setAdministrativeDivision($this->getReference('administrative-division-' . $p['ad']));
            $obj->setCreatedBy($user);
            $om->persist($obj);
            $this->setReference('place-' . $p['name'], $obj);
        }

        $om->flush();
    }
}