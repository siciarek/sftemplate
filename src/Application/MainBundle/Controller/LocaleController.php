<?php

namespace Application\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;

/**
 * @Route("/locale")
 */
class LocaleController extends Controller
{
    protected static $locales = array(
        "en",
        "pl",
    );

    protected $default_locale = "en";

    /**
     * @Route("/widget", name="_locale_widget")
     * @Template("ApplicationMainBundle:Common:locale.html.twig")
     */
    public function localeWidgetAction() {

        return array(
            "locales" => self::$locales
        );
    }

    /**
     * @Route("/change/{locale}", name="_locale_change", requirements = {"locale"="^[a-z]{2}$"})
     * @Template()
     */
    public function changeLocaleAction($locale) {

        $locale = in_array($locale, self:: $locales) ? $locale : $this->default_locale;

        $session = $this->getRequest()->getSession();
        $session->set("locale", $locale);

        $referer = $this->getRequest()->server->get("HTTP_REFERER");

        // Handling dev environent when no referer was found.
        if($referer === null) {
            $script_name = $this->getRequest()->getScriptName();
            $referer = $this->getRequest()->getSchemeAndHttpHost();
            $referer .= preg_match("/dev/", $script_name) > 0 ? $script_name . "/" : "";
        }

        return $this->redirect($referer);
    }
}
