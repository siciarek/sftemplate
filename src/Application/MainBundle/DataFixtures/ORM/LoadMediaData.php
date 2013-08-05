<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Bundle\DemoBundle\DataFixtures\ORM;

use Application\MainBundle\DataFixtures\ORM\BaseFixture;
use Application\Sonata\MediaBundle\Entity\Gallery;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

use Sonata\MediaBundle\Model\GalleryInterface;
use Sonata\MediaBundle\Model\MediaInterface;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;

class LoadMediaData extends BaseFixture
{
    protected $order = 1;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        /**
         * @var Gallery $gallery
         */
        $gallery[0] = $this->getGalleryManager()->create();
        $gallery[0]->setName($this->container->getParameter('app_name'));
        $gallery[0]->setEnabled(true);
        $gallery[0]->setDefaultFormat('small');
        $gallery[0]->setContext('default');

        $gallery[1] = $this->getGalleryManager()->create();
        $gallery[1]->setName('Inne fotografie');
        $gallery[1]->setEnabled(true);
        $gallery[1]->setDefaultFormat('small');
        $gallery[1]->setContext('default');

        $manager = $this->getMediaManager();
        $faker = $this->getFaker();

        $files = Finder::create()
            ->name('*.JPG')
            ->in(__DIR__.'/../data/images');

        $i = 0;

        foreach ($files as $pos => $file) {
            $media = $manager->create();
            $media->setBinaryContent($file);
            $media->setEnabled(true);
            $media->setDescription($faker->sentence(6));
            $manager->save($media, 'default', 'sonata.media.provider.image');
            $this->addMedia($gallery[$i % 2], $media);
            $i++;
        }

        $this->getGalleryManager()->update($gallery[0]);
        $this->getGalleryManager()->update($gallery[1]);
    }

    /**
     * @param \Sonata\MediaBundle\Model\GalleryInterface $gallery
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     * @return void
     */
    public function addMedia(GalleryInterface $gallery, MediaInterface $media)
    {
        $galleryHasMedia = new \Application\Sonata\MediaBundle\Entity\GalleryHasMedia();
        $galleryHasMedia->setMedia($media);
        $galleryHasMedia->setPosition(count($gallery->getGalleryHasMedias()) + 1);
        $galleryHasMedia->setEnabled(true);

        $gallery->addGalleryHasMedias($galleryHasMedia);
    }

    /**
     * @return \Sonata\MediaBundle\Model\MediaManagerInterface
     */
    public function getMediaManager()
    {
        return $this->container->get('sonata.media.manager.media');
    }

    /**
     * @return \Sonata\MediaBundle\Model\MediaManagerInterface
     */
    public function getGalleryManager()
    {
        return $this->container->get('sonata.media.manager.gallery');
    }

    /**
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        return $this->container->get('faker.generator');
    }
}