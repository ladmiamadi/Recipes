<?php

namespace App\Controller\User;


use App\Entity\Comments;
use App\Entity\Recipe;
use App\Form\CommentsType;

use App\Form\RecipeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RecipeController extends AbstractController
{


    /**
     * @Route("user/new-recipe", name="recipe_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $recipe = new Recipe();


        $user = $this->getUser();

        $recipe->setUser($user);



        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('user_profile');
        }


        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recipe/{id}", name="recipe_show", methods={"GET","POST"})
     */
    public function show(Recipe $recipe, Request $request): Response
    {
        $comment = new Comments();


        $comment->setRecipe($recipe);
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('sucess', 'votre commentaire a été posté avec succées');
            return $this->redirectToRoute('recipe_show', [

                'recipe' => $recipe, 'form' => $form->createView(), 'id' => $recipe->getId()
            ]);
        }
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,  'form' => $form->createView()

        ]);
    }
}
