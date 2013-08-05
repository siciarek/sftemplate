<?php

namespace Application\MainBundle\DataFixtures\ORM;

use Application\MainBundle\Entity\Event;
use Application\MainBundle\Entity\Page;
use Application\MainBundle\Entity\Participant;
use Application\MainBundle\Entity\Participation;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadPageData extends BaseFixture
{
    protected $order = 5;
    public $count = 0;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {
        return;

        $pages = array(
            array(
                'title'   => 'Str. główna',
                'home'    => true,
                'enabled' => true,
                'content' => file_get_contents(__DIR__ . '/../data/home.html')
            ),
            array(
                'title'   => 'O konferencji',
                'enabled' => true,
                'content' => file_get_contents(__DIR__ . '/../data/about.html')
            ),
            array(
                'title'   => 'Program',
                'enabled' => true,
                'content' => file_get_contents(__DIR__ . '/../data/agenda.html')
            ),
            array(
                'title'   => 'Prelegenci',
                'enabled' => true,
                'content' => 'Zawartość tej strony jest generowana automatycznie. Można tylko zmienić jej pozycję w menu.',
            ),
            array(
                'title'   => 'Cennik',
                'enabled' => true,
                'content' => ''
                . "<h1>Cennik</h1>\n\n"
                . file_get_contents(__DIR__ . '/../data/cennik/regular.html')
                . file_get_contents(__DIR__ . '/../data/cennik/ipma.html')
                . file_get_contents(__DIR__ . '/../data/cennik/student.html')
                . '<div>&nbsp;</div>'
                . file_get_contents(__DIR__ . '/../data/cennik/regulations.html')
            ),
            array(
                'title'   => 'Zarejestruj się',
                'enabled' => true,
                'content' => 'Zawartość tej strony jest generowana automatycznie. Można tylko zmienić jej pozycję w menu.',
            ),
            array(
                'title'   => 'Lokalizacja',
                'enabled' => true,
                'content' => file_get_contents(__DIR__ . '/../data/location.html')
            ),

            array(
                'title'   => 'Kontakt',
                'enabled' => true,
                'content' => file_get_contents(__DIR__ . '/../data/contact.html')
            ),

            array(
                'title'   => 'Polityka prywatności serwisu',
                'enabled' => true,
                'content' => file_get_contents(__DIR__ . '/../data/privacypolicy.html')
            ),
        );

        foreach ($pages as $obj) {
            $p = new Page();
            $p->setTitle($obj['title']);
            $p->setContent($obj['content']);
            $p->setEnabled($obj['enabled']);
            $p->setHome(array_key_exists('home', $obj) and $obj['home']);
            $om->persist($p);
        }

        $om->flush();
    }
}