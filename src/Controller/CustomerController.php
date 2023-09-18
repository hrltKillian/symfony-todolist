<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    #[Route('/customers', name: 'app_customers')]
    public function index(
        CustomerRepository $customerRepository,
        PaginatorInterface $paginatorInterface,
        Request $request
    ): Response
    {

        $pagination = $paginatorInterface->paginate(
            $customerRepository->createQueryBuilder("c"), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page*/
        );

        return $this->render('customer/index.html.twig', [
            'customers' => $pagination,
        ]);
    }

    #[Route('/customers/{slug}', name: 'app_customers_show')]
    public function show(
        Customer $customer
    ): Response
    {
        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route('/customers/new', name: 'app_customers_new', priority:2)]
    public function new(
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ): Response
    {
        $form = $this->createForm(CustomerType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $customer = $form->getData();
            $entityManagerInterface->persist($customer);
            $entityManagerInterface->flush();
            $this->addFlash("success", "Vous avez bien créé votre compte client.");
            return $this->redirectToRoute("app_customers");
        }
        return $this->render('customer/new.html.twig', [
            "form" => $form,
        ]);
    }

    #[Route('/customers/{slug}/edit', name: 'app_customers_edit')]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        Customer $customer
    ): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $entityManagerInterface->flush();
            $this->addFlash("success", "Vous avez bien édité votre compte client.");
            return $this->redirectToRoute("app_customers");
        }
        return $this->render('customer/edit.html.twig', [
            "form" => $form,
            "customer" => $customer
        ]);
    }

    #[Route('/customers/{slug}/delete', name: 'app_customers_delete')]
    public function delete(
        EntityManagerInterface $entityManagerInterface,
        Customer $customer
    ): Response
    {
        $entityManagerInterface->remove($customer);
        $entityManagerInterface->flush();
        $this->addFlash("success", "Vous avez bien supprimé votre compte client.");
        return $this->redirectToRoute("app_customers");
    }
}
