<?php

namespace App\Controller\Admin;


use App\Entity\Comments;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Form\CommentsType;
use App\Form\IngredientType;
use App\Form\RecipeType;
use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminIngredientController extends AbstractController
{


    /**
     * @Route("/admin/ingredient", name="ingredient_home_admin", methods={"GET","POST"})
     */
    public function index(IngredientRepository $rep): Response
    {

        $ingredients = $rep->findAll();
        return $this->render('admin/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    /**
     * @Route("admin/new-ingredient", name="ingredient_new_admin", methods={"GET","POST"})
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
            $this->addFlash('sucess', 'votre ingrédient a été ajouté avec succées');

            return $this->redirectToRoute('ingredient_home_admin');
        }


        return $this->render('admin/ingredient/new.html.twig', [
            'ingredients' => $ingredient,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("admin/edit-ingredient-{id}", name="ingredient_edit_admin", methods={"GET","POST"})
     */
    public function edit(Request $request, Ingredient $ingredient): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ingredient_home_admin');
        }

        return $this->render('admin/ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/supprimer-ingredient-{id}", name="ingredient_delete_admin", methods={"DELETE"})
     */
    public function delete(Request $request, Ingredient $ingredient): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ingredient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ingredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ingredient_home_admin');
    }
}
