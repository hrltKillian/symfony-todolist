<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('customers/{customer_id}/tasks', name: 'app_tasks')]
    public function show(
        int $customer_id,
        TaskRepository $taskRepository
    ): Response
    {

        return $this->render('task/show.html.twig', [
            'tasks' => $taskRepository->findAll(),
            "customer_id" => $customer_id
        ]);
    }

    #[Route('customers/{customer_id}/tasks/new', name: 'app_tasks_new')]
    public function new(
        int $customer_id,
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ): Response
    {
        $form = $this->createForm(TaskType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $entityManagerInterface->persist($task);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("app_tasks",array('customer_id' => $customer_id));
        }


        return $this->render('task/new.html.twig', [
            "form" => $form,
        ]);
    }

    #[Route('customers/{customer_id}/tasks/{id<\d+>}/edit', name: 'app_tasks_edit')]
    public function edit(
        int $customer_id,
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        Task $task
    ): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $entityManagerInterface->flush();

            return $this->redirectToRoute("app_tasks",array('customer_id' => $customer_id));
        }

        return $this->render('task/edit.html.twig', [
            "form" => $form,
            "task" => $task
        ]);
    }

    #[Route('customers/{customer_id}/tasks/{id<\d+>}/delete', name: 'app_tasks_delete')]
    public function delete(
        int $customer_id,
        EntityManagerInterface $entityManagerInterface,
        Task $task
    ): Response
    {
        $entityManagerInterface->remove($task);
        $entityManagerInterface->flush();

        return $this->redirectToRoute("app_tasks",array('customer_id' => $customer_id));

    }
}
