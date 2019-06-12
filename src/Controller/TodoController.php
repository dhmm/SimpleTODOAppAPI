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

        $task = new Task();
        $task->setName($request->get('name'));
        $task->setDone($request->get('done'));
        $em->persist($task);
        $em->flush();

        return $this->respondCreated($taskRepository->transform($task));

    }
    /**
     * @Route("/todo" , methods="PUT")
     */
    public function update(Request $request, TaskRepository $taskRepository, EntityManagerinterface $em) {
        $request = $this->transformJsonBody($request);
        if(! $request) {
            return $this->respondValidationError('Not valid request');
        }

        if(! $request->get('id')) {
            return $this->respondValidationError('Enter an ID');
        }

        if(! $request->get('name')) {
            return $this->respondValidationError('Enter a task name');
        }
        
        if(! $request->get('done')) {
            return $this->respondValidationError('Enter the done status');
        }

        $task = $taskRepository->find($request->get('id'));
        if(! $task) {
            return $this->respondWithError();
        }
        $task->setName($request->get('name'));
        $task->setDone($request->get('done'));
        $em->flush();

        return $this->respondUpdated($taskRepository->transform($task));
    }
    /**
     * @Route("/todo" , methods="DELETE")
     */
    public function delete(Request $request, TaskRepository $taskRepository, EntityManagerinterface $em) {
        $request = $this->transformJsonBody($request);
        if(! $request) {
            return $this->respondValidationError('Not valid request');
        }

        if(! $request->get('id')) {
            return $this->respondValidationError('Enter an ID');
        }

        $task = $taskRepository->find($request->get('id'));
        if(! $task) {
            return $this->respondWithErrors();
        }

        $em->remove($task);
        $em->flush();

        $task = $taskRepository->find($request->get('id'));
        if(! $task) {
            return $this->respondDeleted();
        } else {
            return $this->respondWithErrors();
        }        
    }

    /**
     * @Route("/todo/complete" , methods="POST")
     */
    public function complete(Request $request, TaskRepository $taskRepository, EntityManagerInterface $em) {
      $request = $this->transformJsonBody($request);
      if(! $request) {
          return $this->respondValidationError('Not valid request');
      }

      if(! $request->get('id')) {
          return $this->respondValidationError('Enter an ID');
      }

      $task = $taskRepository->find($request->get('id'));
      if(! $task) {
          return $this->respondWithError();
      }      
      $task->setDone(true);
      $em->flush();

      return $this->respondUpdated($taskRepository->transform($task));
    }

    /**
     * @Route("/todo/uncomplete" , methods="POST")
     */
    public function uncomplete(Request $request, TaskRepository $taskRepository, EntityManagerInterface $em) {
      $request = $this->transformJsonBody($request);
      if(! $request) {
          return $this->respondValidationError('Not valid request');
      }

      if(! $request->get('id')) {
          return $this->respondValidationError('Enter an ID');
      }

      $task = $taskRepository->find($request->get('id'));
      if(! $task) {
          return $this->respondWithError();
      }      
      $task->setDone(false);
      $em->flush();

      return $this->respondUpdated($taskRepository->transform($task));
    }
}
