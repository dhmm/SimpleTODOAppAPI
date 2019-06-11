<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Task::class);
    }    
    public function transform(Task $task) {
        return [
            'id' => (int) $task->getId(),
            'name' => (string) $task->getName(),
            'done' => (boolean) $task->getDone()
        ];
    }

    public function transformAll() {
        $tasks = $this->findAll();
        $tasksArray = [];

        foreach($tasks as $task) {
            $tasksArray[] = $this->transform($task);
        }

        return $tasksArray;
    }
}
