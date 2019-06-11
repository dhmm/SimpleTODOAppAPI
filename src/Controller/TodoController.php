<?php
//src/Controller/TodoController

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TodoController 
{
    /**
     * @Route("/todo")
     */
    public function index() {
        return new JsonResponse([
            [
                'id' => '1' ,
                'title' => 'Title is here'
            ]
        ]);
    }
}
