<?php
namespace Scavenger\WebserviceBundle\Lib\Response;

use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpKernel\Exception\HttpException;

class ResponseHandler {

    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    private $response;
    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Returns Response in JSON Format, given value has to be some encodeable Stuff (most likely an array ;) )
     * @param $value
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleResponse($value)
    {
        $this->throwNotFound($value);

        $this->response->setStatusCode(200);
        $this->response->setContent(json_encode($value));
        $this->response->headers->set('Content-Type', 'application/json');

        return $this->response;

    }

    /**
     * Throws a 404 exception if given value is not set (false, null, empty array)
     * @param $value
     * @return void
     */
    private function throwNotFound($value)
    {
        if (null == $value) {
            throw new HttpException(404, 'Not found');
        }
    }

}
