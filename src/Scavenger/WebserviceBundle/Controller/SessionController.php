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
        $userRepository = $this->container->get('sh.repository.user');

        $user = $userRepository->findOneBy(array('id' => $session->getMrX()));

        if (!$user) {
            throw new HttpException(400, 'User (mrX) does not exist');
        }

        $sessions = $user->getSessions();

        $sessions->add($session);
        $user->setSessions($sessions);
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

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
     * @Route("/{sessionId}/user/{userId}/")
     * @Method({"DELETE"})
     */
    public function removeUserAction($sessionId, $userId)
    {
        $userRepository = $this->container->get('sh.repository.user');
        $sessionRepository = $this->getSessionRepository();

        $user = $userRepository->findOneBy(array('id' => $userId));
        $session = $sessionRepository->findOneBy(array('id' => $sessionId));

        $sessions = $user->getSessions();

        if ($sessions->contains($session)) {
            $sessions->removeElement($session);
            $user->setSessions($sessions);

            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();
        }


        return new Response('', 200);
    }

    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function getSessionsAction()
    {
        $repository = $this->getSessionRepository();
        $sessions = $repository->findAll();
        $responseHandler = $this->getResponseHandler();

        return $responseHandler->handleResponse($this->getSessionsAsArray($sessions));
    }

    /**
     * @Route("/{id}/")
     * @Method({"GET"})
     */
    public function getSessionAction($id)
    {
        $repository = $this->getSessionRepository();
        $session = $repository->findOneBy(array('id' => $id));
        $responseHandler = $this->getResponseHandler();

        return $responseHandler->handleResponse($this->getSessionAsArray($session));
    }

    /**
     * @Route("/{id}/")
     * @Method({"POST"})
     */
    public function editSessionAction($id)
    {
        $repository = $this->getSessionRepository();
        $session = $repository->findOneBy(array('id' => $id));

        $session = $this->saveSessionData($session, $this->getRequest());
        $responseHandler = $this->getResponseHandler();

        return $responseHandler->handleResponse($this->getSessionAsArray($session));
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
        $session->setStatusCode($request->get('status', $session->getStatusCode()));
        $session->setCauser($request->get('causer', $session->getCauser()));

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
            'status' => $session->getStatusCode(),
            'causer' => $session->getCauser()
        );
    }

    private function getSessionsAsArray(array $sessions)
    {
        $res = array();
        foreach ($sessions as $s) {
            $res[] = $this->getSessionAsArray($s);
        }

        return $res;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $this->get("doctrine.orm.entity_manager");
    }


}