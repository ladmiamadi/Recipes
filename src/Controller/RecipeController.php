<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Rating;
use App\Entity\Recipe;
use App\Form\CommentsType;

use App\Form\RecipeType;
use App\Repository\CommentsRepository;
use App\Repository\QuantityRepository;
use App\Repository\RatingRepository;
use App\Repository\RecipeRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recipe")
 */
class RecipeController extends AbstractController
{
    /**
     * @Route("/", name="recipe_index", methods={"GET"})
     */
    public function index(RecipeRepository $recipeRepository): Response
    {

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="recipe_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $recipe = new Recipe();

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_show", methods={"GET","POST"})
     */
    public function show(Recipe $recipe, QuantityRepository $repository, Request $request, CommentsRepository $rep, RatingRepository $repNote): Response
    {
        $quantities = $repository->findOneByIdJoinedToIngredient($recipe->getId());



        $comment = new Comments();

        $comments = $rep->findById($recipe->getId());
        $note = $repNote->findOneByRecipe($recipe->getId());
        dump($note);
        if ($note == null) {
            $note = 0;
        } else {
            $note = $note['note'];
        }


        $comment->setRecipe($recipe);
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('sucess', 'votre commentaire a été posté avec succées');
            return $this->redirectToRoute('recipe_show', [
                'id' => $recipe->getId(),
                'recipe' => $recipe, 'quantities' => $quantities, 'form' => $form->createView(), 'id' => $recipe->getId()
            ]);
        }
        return $this->render('admin/show.html.twig', [
            'recipe' => $recipe, 'quantities' => $quantities,  'form' => $form->createView(), 'comments' => $comments,
            'note' => $note
        ]);
    }

    /**
     * @Route("/{id}/edit", name="recipe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Recipe $recipe): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recipe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('recipe_index');
    }
}
