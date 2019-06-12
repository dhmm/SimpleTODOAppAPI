<?php
//src/Controller/TodoController

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController 
{  
    protected $statusCode = 200;
    public function getStatusCode() {
        return $this->statusCode;
    }
    protected function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
        return $this;
    }
    public function respond($data, $headers = []) {
        return new JsonResponse($data , $this->getStatusCode() , $headers);
    }
    public function respondWithErrors($errors = ['An error ocurred'] , $headers= []) {
        $data = [
            'errors' => $errors,
        ];
        return new JsonResponse($data, $this->getStatusCode() , $headers);
    }
    public function respondUnauthorized($message= 'Not authorized') {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }
    public function respondValidationError($message = 'Validation errors') {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }
    public function respondNotFound($message = 'Not found') {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }
    public function respondCreated($data = []) {
        return $this->setStatusCode(201)->respond($data);
    }
    public function respondUpdated($data = []) {
        return $this->setStatusCode(201)->respond($data);
    }
    public function respondDeleted() {
        return $this->setStatusCode(201)->respond([]);
    }
    protected function transformJsonBody(Request $request) {
        $data = json_decode($request->getContent() , true);

        if(json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        if($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}