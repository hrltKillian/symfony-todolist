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
    #[Route('customers/{customer_slug}/tasks', name: 'app_tasks')]
    public function show(
        string $customer_slug,
        TaskRepository $taskRepository
    ): Response
    {
        return $this->render('task/show.html.twig', [
            'tasks' => $taskRepository->findAll(),
            "customer_slug" => $customer_slug
        ]);
    }

    #[Route('customers/{customer_slug}/tasks/new', name: 'app_tasks_new')]
    public function new(
        string $customer_slug,
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ): Response
    {
        $form = $this->createForm(TaskType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $task = $form->getData();
            $entityManagerInterface->persist($task);
            $entityManagerInterface->flush();
            $this->addFlash("success", "Vous avez bien créé votre tâche.");
            return $this->redirectToRoute("app_tasks",array('customer_slug' => $customer_slug));
        }
        return $this->render('task/new.html.twig', [
            "form" => $form,
        ]);
    }

    #[Route('customers/{customer_slug}/tasks/{slug}/edit', name: 'app_tasks_edit')]
    public function edit(
        string $customer_slug,
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        Task $task
    ): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $entityManagerInterface->flush();
            $this->addFlash("success", "Vous avez bien modifié votre tâche.");
            return $this->redirectToRoute("app_tasks",array('customer_slug' => $customer_slug));
        }
        return $this->render('task/edit.html.twig', [
            "form" => $form,
            "task" => $task
        ]);
    }

    #[Route('customers/{customer_slug}/tasks/{slug}/delete', name: 'app_tasks_delete')]
    public function delete(
        string $customer_slug,
        EntityManagerInterface $entityManagerInterface,
        Task $task
    ): Response
    {
        $entityManagerInterface->remove($task);
        $entityManagerInterface->flush();
        $this->addFlash("success", "Vous avez bien supprimé votre tâche.");
        return $this->redirectToRoute("app_tasks",array('customer_slug' => $customer_slug));
    }
}
