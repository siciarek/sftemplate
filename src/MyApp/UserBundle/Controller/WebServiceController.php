<?php

namespace MyApp\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;

use MyApp\UserBundle\Entity as E;
use MyApp\UserBundle\Logic\Encoder  ;


class WebServiceController extends Controller
{
    private $frames = array();

    public function serviceAction()
    {
        $frame = array();
        $data = array();

        try {
            $data = array(1, 2, Encoder::encode("helloworld"));

            $frame = $this->frames["ok"];
            $frame["data"] = $data;
        }
        catch(\Exception $e) {
            $frame = $this->frames["error"];
            $frame["msg"] = $e->getMessage();
            $frame["data"]["errno"] = $e->getCode();
        }

        $json = json_encode($frame);
        return new Response($json);
    }

    public function failureAction()
    {
        $frame = array();
        $data = array();

        try {

            throw new \Exception("Test error", 666);

            $frame = $this->frames["ok"];
            $frame["data"] = $data;
        }
        catch(\Exception $e) {
            $frame = $this->frames["error"];
            $frame["msg"] = $e->getMessage();
            $frame["data"]["errno"] = $e->getCode();
        }

        $json = json_encode($frame);
        return new Response($json);
    }

    public function preExecute() {
        $this->frames["ok"] = array(
            "success" => true,
            "type" => "info",
            "datetime" => date("Y-m-d H:i:s"),
            "msg" => "OK",
            "data" => array(),
        );

        $this->frames["error"] = array(
            "success" => true,
            "type" => "error",
            "datetime" => date("Y-m-d H:i:s"),
            "msg" => "Unexpected Exception",
            "data" => array(),
        );
    }
}
