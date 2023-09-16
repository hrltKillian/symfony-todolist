<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    #[Route('/customers', name: 'app_customers')]
    public function index(
        CustomerRepository $customerRepository
    ): Response
    {
        return $this->render('customer/index.html.twig', [
            'customers' => $customerRepository->findAll(),
        ]);
    }

    #[Route('/customers/{id<\d+>}', name: 'app_customers_show')]
    public function show(
        Customer $customer
    ): Response
    {
        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route('/customers/new', name: 'app_customers_new')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ): Response
    {
        $form = $this->createForm(CustomerType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            $entityManagerInterface->persist($customer);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("app_customers");
        }

        return $this->render('customer/new.html.twig', [
            "form" => $form,

        ]);
    }

    #[Route('/customers/{id<\d+>}/edit', name: 'app_customers_edit')]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        Customer $customer
    ): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $entityManagerInterface->flush();

            return $this->redirectToRoute("app_customers");
        }

        return $this->render('customer/edit.html.twig', [
            "form" => $form,
            "customer" => $customer
        ]);
    }

    #[Route('/customers/{id<\d+>}/delete', name: 'app_customers_delete')]
    public function delete(
        EntityManagerInterface $entityManagerInterface,
        Customer $customer
    ): Response
    {

        $entityManagerInterface->remove($customer);
        $entityManagerInterface->flush();
        return $this->redirectToRoute("app_customers");
        
    }
}
