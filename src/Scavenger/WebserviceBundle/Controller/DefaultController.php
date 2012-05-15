<?php

namespace Scavenger\WebserviceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Symfony\Component\HttpFoundation\Response;
use \Scavenger\WebserviceBundle\Entity\User;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/user")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Method({"POST"})
     */
    public function userCreateAction()
    {
        /**
         * @var \Symfony\Component\HttpFoundation\Request
         */
        $request = $this->getRequest();
        $user = new User();

        $user->setName($request->get('name'));
        $user->setDeviceId($request->get('deviceId'));

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        $response = new Response();
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * @Route("/{id}/")
     * @Method({"POST"})
     */
    public function userEditAction($id)
    {
        /**
         * @var \Symfony\Component\HttpFoundation\Request
         */
        $request = $this->getRequest();
        $user = $this->getUserRepository()->findOneById($id);

        $this->assertUserExists($user);

        $this->saveUserData($user, $this->getRequest());

        $response = new Response();
        $response->setStatusCode(200);

        $response->setContent(json_encode(array('foo' => 'var')));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    private function saveUserData(User $user, $request)
    {
        $repository = $this->getUserRepository();

        $user->setName($request->get('name', $user->getName()));
        $user->setDeviceId($request->get('deviceId', $user->getDeviceId()));

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

    }


    /**
     * @return \Scavenger\WebserviceBundle\Entity\UserRepository
     */
    private function getUserRepository()
    {
        return $this->container->get('sh.repository.user');
    }

    private function getEntityManager()
    {
        return $this->get("doctrine.orm.entity_manager");
    }

    private function assertUserExists($user)
    {
        if (null == $user) {
            throw new HttpException(404, 'User not found');
        }
    }
}
