<?php

namespace App\Controller\Admin;


use App\Entity\Comments;
use App\Entity\Recipe;
use App\Form\CommentsType;

use App\Form\RecipeType;
use App\Repository\QuantityRepository;
use App\Repository\RecipeRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminRecipeController extends AbstractController
{


    /**
     * @Route("admin/recipe", name="recipe_home_admin")
     *
     */
    public function index(Request $request, RecipeRepository $rep)
    {
        $recipe = $rep->findAll();
        return $this->render('admin/recipe/index.html.twig', ['recipes' => $recipe]);
    }
    /**
     * @Route("admin/new-recipe", name="recipe_new_admin", methods={"GET","POST"})
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

            return $this->redirectToRoute('recipe_home_admin');
        }


        return $this->render('admin/recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/recipe/{id}", name="recipe_show", methods={"GET","POST"})
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

    /**
     * @Route("admin/recipe-edit-{id}", name="recipe_edit_admin", methods={"GET","POST"})
     */
    public function edit(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin/recipe');
        }

        return $this->render('admin/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/{id}", name="recipe_delete_admin", methods={"DELETE"})
     */
    public function delete(Request $request, Recipe $recipe, QuantityRepository $rep): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recipe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->remove($recipe->getQuantities());

            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('recipe_home_admin');
    }
    /**
     * @Route("admin/activer-recipe/{id}/", name="recipe_activer_admin")
     */
    public function activer(Recipe $recipe, Request $request)
    {

        $recipe->setImageFile(new File($recipe->getImageFile()));



        $recipe->setIsVerified(($recipe->getIsVerified()) ? false : true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($recipe);
        $em->flush();

        return new JsonResponse(
            array(
                'status' => 'success',
                'message' => 1
            ),
            200
        );
    }
}
