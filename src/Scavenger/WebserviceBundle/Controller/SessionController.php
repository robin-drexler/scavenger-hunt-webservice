<?php

namespace Scavenger\WebserviceBundle\Controller;

use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Exception\HttpException;
use \Scavenger\WebserviceBundle\Entity\Session;
use \Scavenger\WebserviceBundle\Entity\User;
use \Scavenger\WebserviceBundle\Entity\Battlezone;

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
        $session = $this->prepareSessionData(new Session(), $this->getRequest());
        $battlezone = $this->prepareBattlezoneData(new Battlezone(), $this->getRequest());
        $session->setBattlezone($battlezone);

        //set the start time, cause obviously this is the start of the session
        $session->setStart(time());
        
        $userRepository = $this->container->get('sh.repository.user');

        $user = $userRepository->find($session->getMrX());




        if (!$user) {
            throw new HttpException(400, 'User (mrX) does not exist');
        }
        
        //If no problens occured, save the bunch of data
        $em = $this->getEntityManager();
        $em->persist($session);
        $em->flush();

        $sessions = $user->getSessions();

        //Add session to current user session
        $sessions->add($session);
        $user->setSessions($sessions);
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
        $session = $repository->find($id);

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

        $user = $userRepository->find($userId);
        $session = $sessionRepository->find($sessionId);

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

        $user = $userRepository->find($userId);
        $session = $sessionRepository->find($sessionId);

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
        $session = $repository->find($id);
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
        $session = $repository->find($id);

        $session = $this->prepareSessionData($session, $this->getRequest());
        
        $em = $this->getEntityManager();
        $em->persist($session);
        $em->flush();
        
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

    private function prepareSessionData(Session $session, $request)
    {
        $session->setName($request->get('name', $session->getName()));
        $session->setMrX($request->get('mrX', $session->getMrX()));
        $session->setStatusCode($request->get('status', $session->getStatusCode()));
        $session->setCauser($request->get('causer', $session->getCauser()));

        return $session;
    }

    private function prepareBattlezoneData(Battlezone $battlezone, Request $request)
    {
        $battlezone->setName(   $request->get('battlezone_name'),   $battlezone->getName());
        $battlezone->setLat(    $request->get('battlezone_lat'),    $battlezone->getLat());
        $battlezone->setLng(    $request->get('battlezone_lng'),    $battlezone->getLng());
        $battlezone->setRadius( $request->get('battlezone_radius'), $battlezone->getRadius());

        return $battlezone;
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

        $battlezone = $session->getBattlezone();

        $battlezoneAr = array();

        if ($battlezone) {
            $battlezoneAr = array(
                'name' => $battlezone->getName(),
                'lat'  => $battlezone->getLat(),
                'lng' => $battlezone->getLng(),
                'radius' => $battlezone->getRadius()
            );
        }

        return array(
            'id' => $session->getId(),
            'name' => $session->getName(),
            'mrX' => $session->getMrX(),
            'status' => $session->getStatusCode(),
            'causer' => $session->getCauser(),
            'start' => $session->getStart(),
            'battlezone' => $battlezoneAr
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