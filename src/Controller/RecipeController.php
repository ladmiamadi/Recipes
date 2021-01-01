<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Rating;
use App\Entity\Recipe;
use App\Form\CommentsType;
use App\Form\Recipe1Type;
use App\Repository\CommentsRepository;
use App\Repository\QuantityRepository;
use App\Repository\RatingRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\Repository\RepositoryFactory;
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
    public function new(Request $request, Rating $rating): Response
    {
        $recipe = new Recipe();
        $recipe->setRating(0);
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
    public function show(Recipe $recipe, QuantityRepository $repository, Request $request, CommentsRepository $rep, Rating $note, RatingRepository $repNote): Response
    {
        $quantities = $repository->findOneByIdJoinedToIngredient($recipe->getId());



        $comment = new Comments();

        $comments = $rep->findById($recipe->getId());
        $note = $repNote->findOneByRecipe($recipe->getId());


        $comment->setRecipe($recipe);
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('sucess', 'votre commentaire a été posté avec succées');
            return $this->redirectToRoute('recipe_show', [
                'recipe' => $recipe, 'quantities' => $quantities, 'form' => $form->createView(), 'id' => $recipe->getId(), 'note' => $note->getNote()
            ]);
        }
        return $this->render('admin/show.html.twig', [
            'recipe' => $recipe, 'quantities' => $quantities, 'note' => $note, 'form' => $form->createView(), 'comments' => $comments
        ]);
    }

    /**
     * @Route("/{id}/edit", name="recipe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(Recipe1Type::class, $recipe);
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
