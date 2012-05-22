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
use \Scavenger\WebserviceBundle\Entity\Session;

/**
 * @Route("/session")
 */
class SessionController extends Controller
{
    /**
     * @Route("/")
     * @Method({"POST"})
     */
    public function createAction()
    {
        $session = $this->saveSessionData(new Session(), $this->getRequest());

        return $this->getResponseHandler()->handleResponse($this->getSessionAsArray($session));
    }

    /**
     * @Route("/{id}/")
     * @Method({"DELETE"})
     */
    public function deleteAction($id)
    {
        $repository = $this->getSessionRepository();
        $session = $repository->findOneBy(array('id' => $id));

        $responseHandler = $this->getResponseHandler();
        $responseHandler->throwNotFound($session);

        $em = $this->getEntityManager();
        $em->remove($session);
        $em->flush();

        return new Response('', 200);
    }

    /**
     * curl -v -d "" http://localhost/scavenger-bk/scavenger-hunt-webservice/web/app_dev.php/session/100/user/2/
     * @Route("/{sessionId}/user/{userId}/")
     * @Method({"POST"})
     */
    public function addUserAction($sessionId, $userId)
    {
        $userRepository = $this->container->get('sh.repository.user');
        $sessionRepository = $this->getSessionRepository();

        $user = $userRepository->findOneBy(array('id' => $userId));
        $session = $sessionRepository->findOneBy(array('id' => $sessionId));

        $sessions = $user->getSessions();

        if (!$sessions->contains($session)) {
            $sessions->add($session);
            $user->setSessions($sessions);
            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();
        }

        return new Response('', 200);
    }

    /**
     * @return \Scavenger\WebserviceBundle\Entity\SessionRepository
     */
    private function getSessionRepository()
    {
        return $this->container->get('sh.repository.session');
    }

    /**
     * @return \Scavenger\WebserviceBundle\Lib\Response\ResponseHandler
     */
    private function getResponseHandler()
    {
        return $this->container->get('sh.response.handler');
    }

    private function saveSessionData(Session $session, $request)
    {
        $session->setName($request->get('name', $session->getName()));
        $session->setMrX($request->get('mrX', $session->getMrX()));


        $em = $this->getEntityManager();
        $em->persist($session);
        $em->flush();

        return $session;
    }

    /**
     * @param \Scavenger\WebserviceBundle\Entity\Session $session | null
     * @return array
     */
    private function getSessionAsArray($session)
    {
        if (!$session) {
            return array();
        }

        return array(
            'id' => $session->getId(),
            'name' => $session->getName(),
            'mrX' => $session->getMrX(),
        );
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $this->get("doctrine.orm.entity_manager");
    }


}