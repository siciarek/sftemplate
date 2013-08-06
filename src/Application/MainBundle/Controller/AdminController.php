<?php

namespace Application\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends Controller
{
    protected $output = array();

    /**
     * @Route("/{class}/{id}/move-{direction}.html", name="_move_object", requirements = {"id"="^[0-9]\d*$", "direction"="^(up|down)$"})
     */
    public function moveObjectAction($class, $id, $direction)
    {
        $this->em = $this->getDoctrine()->getManager();
        $obj = $this->em
            ->getRepository("ApplicationMainBundle:" . $class)
            ->find($id)
        ;

        $directions = array(
            "up" => -1,
            "down" => 1,
        );

        $obj->setPosition($obj->getPosition() + $directions[$direction]);
        $this->em->persist($obj);
        $this->em->flush();

        $referer = $this->getRequest()->server->get('HTTP_REFERER');
        $referer = $referer == null ? $this->generateUrl("application_main_homepage") : $referer;

        return $this->redirect($referer);
    }
}
