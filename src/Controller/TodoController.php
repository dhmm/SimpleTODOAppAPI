<?php
//src/Controller/TodoController

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class TodoController extends ApiController
{
    /**
     * @Route("/todo" , methods="GET")
     */
    public function index(TaskRepository $taskRepository) {
        $tasks = $taskRepository->transformAll();
        return $this->respond($tasks);
    }
    /**
     * @Route("/todo" , methods="POST")
     */
    public function create(Request $request, TaskRepository $taskRepository, EntityManagerInterface $em) {
        $request = $this->transformJsonBody($request);
        if(! $request) {
            return $this->respondValidationError('Not valid request');
        }

        if(! $request->get('name')) {
            return $this->respondValidationError('Enter a task name');
        }
        
        if(! $request->get('done')) {
            return $this->respondValidationError('Enter the done status');
        }

        $task = new Task;
        $task->setName($request->get('name'));
        $task->setDone($request->get('done'));
        $em->persist($task);
        $em->flush();

        return $this->respondCreated($taskRepository->transform($task));

    }
}
