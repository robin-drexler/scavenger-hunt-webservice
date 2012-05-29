<?php

namespace Scavenger\WebserviceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



/**
 * @Route("/datetime")
 */
class DateController extends Controller
{

    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function getUTCTimestampAction()
    {
        return $this->container->get('sh.response.handler')->handleResponse(array(time()));
    }
}
