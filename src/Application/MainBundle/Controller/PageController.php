<?php

namespace Application\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;

/**
 * @Route("/")
 */
class PageController extends Controller
{
    /**
     * @Route("/page/{slug}", name="_page_en")
     * @Route("/strona/{slug}", name="_page_pl")
     * @Template()
     */
    public function indexAction($slug)
    {
        $page = $this->getDoctrine()->getManager()->getRepository("ApplicationMainBundle:Page")->findOneBy(
            array("slug" => $slug, "enabled" => true)
        );

        if ($page === null) {
            throw $this->createNotFoundException();
        }

        return array(
            'page' => $page,
        );
    }

    public function getPages($queryName = "sorted")
    {
        $pages = $this->getDoctrine()->getManager()
            ->getRepository("ApplicationMainBundle:Page")
            ->createNamedQuery($queryName)
            ->getResult();

        return $pages;
    }
}
