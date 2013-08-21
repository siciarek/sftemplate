<?php

namespace Application\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class GamesController extends Controller
{
    /**
     * @Route("/memory/{size}", defaults={"size":"small"}, requirements={"size":"^(small|medium|big)$"}, name="_games_memory")
     * @Template()
     */
    public function memoryAction($size)
    {
        return array();
    }

    /**
     * @Route("/kulki/{size}", defaults={"size":"small"}, requirements={"size":"^(small|medium|big)$"}, name="_games_kulki")
     * @Template()
     */
    public function kulkiAction($size)
    {
        return array();
    }
}
