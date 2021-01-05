<?php

namespace App\Controller;

use App\Entity\Quantity;
use App\Form\QuantityType;
use App\Repository\QuantityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/quantity")
 */
class QuantityController extends AbstractController
{
    /**
     * @Route("/", name="quantity_index", methods={"GET"})
     */
    public function index(QuantityRepository $quantityRepository): Response
    {
        return $this->render('quantity/index.html.twig', [
            'quantities' => $quantityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="quantity_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $quantity = new Quantity();

        $form = $this->createForm(QuantityType::class, $quantity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quantity);
            $entityManager->flush();

            return $this->redirectToRoute('quantity_index');
        }

        return $this->render('quantity/new.html.twig', [
            'quantity' => $quantity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quantity_show", methods={"GET"})
     */
    public function show(Quantity $quantity): Response
    {
        return $this->render('quantity/show.html.twig', [
            'quantity' => $quantity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="quantity_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Quantity $quantity): Response
    {
        $form = $this->createForm(QuantityType::class, $quantity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quantity_index');
        }

        return $this->render('quantity/edit.html.twig', [
            'quantity' => $quantity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quantity_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Quantity $quantity): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quantity->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quantity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quantity_index');
    }
}
