<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ingredient")
 */
class IngredientController extends AbstractController
{
    /**
     * @Route("/", name="ingredient_index", methods={"GET"})
     */
    public function index(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new/", name="ingredient_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ingredient);
            $entityManager->flush();

            return $this->redirectToRoute('ingredient_index');
        }


        return $this->render('ingredient/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new/ajax", name="ingredient_ajax", methods={"GET","POST"})
     */
    public function newAjax(Request $request, IngredientRepository $rep)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(
                array(
                    'status' => 'Error',
                    'message' => 'Error'
                ),
                400
            );
        }
        if (isset($request->request)) {
            // Get data from ajax
            $req = $request->request->get('task');

            $ingredient = $rep->findOneBy([
                'name' => $req
            ]);



            if ($ingredient === null) {
                // Folder does not exist
                $ingredient = new ingredient();
                $ingredient->setName($req);
                // Check if a Folder with the given name already exists
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ingredient);
                $entityManager->flush();
                return new JsonResponse(
                    array(
                        'status' => 'OK',
                        'message' => 1
                    ),
                    200
                );
            } else {
                // Folder exists
                return new JsonResponse(
                    array(
                        'status' => 'OK',
                        'message' => 0
                    ),
                    200
                );
            }


            // If we reach this point, it means that something went wrong
            return new JsonResponse(
                array(
                    'status' => 'Error',
                    'message' => 'Error'
                ),
                400
            );
        };
    }

    /**
     * @Route("/{id}", name="ingredient_show", methods={"GET"})
     */
    public function show(Ingredient $ingredient): Response
    {
        return $this->render('ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ingredient_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ingredient $ingredient): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ingredient_index');
        }

        return $this->render('ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ingredient_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ingredient $ingredient): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ingredient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ingredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ingredient_index');
    }


    /** 
     * @Route("/ajax", name="ajax_ingredient", methods={"POST"}) 
     */
    public function ajaxAction(Request $request)
    {


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            return true;
        }
        return false;
    }
}
