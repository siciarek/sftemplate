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
        $pages = array(
            array(
                'title'   => 'Privacy policy',
                'enabled' => true,
                'content' => file_get_contents(__DIR__ . '/../data/privacypolicy.html')
            ),
            array(
                'title'   => 'Terms',
                'enabled' => true,
                'content' => file_get_contents(__DIR__ . '/../data/terms.html')
            ),
            array(
                'title'   => 'Contact',
                'enabled' => true,
                'content' => file_get_contents(__DIR__ . '/../data/contact.html')
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