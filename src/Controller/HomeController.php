<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\RecipeSearch;
use App\Form\RecipeSearchType;
use App\Repository\RatingRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     *
     * index
     *
     * @param  RecipeRepository $repository
     * @return Response
     */
    public function index(RecipeRepository $repository, Request $request): Response
    {
        $search = new RecipeSearch();
        $form = $this->createForm(RecipeSearchType::class, $search);
        $form->handleRequest($request);

        // $recipes = $repository->findAllRecipes($search);

        $recipes = $repository->findRandomRecipes();
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes,
            'formsearch' => $form->createView(),

        ]);
    }
    /**
     * @Route("/categorie/{category}", name="category", methods={"GET"})
     *
     */
    public function category(RecipeRepository $repository, RatingRepository $rep, Request $request, Recipe $reci): Response
    {
        $vote = $rep->findAvgRating();
        $search = new RecipeSearch();
        $form = $this->createForm(RecipeSearchType::class, $search);
        $form->handleRequest($request);

        $recipes = $repository->findByCategory($reci->getCategory());


        return $this->render('home/index.html.twig', [
            'recipes' => $recipes,
            'formsearch' => $form->createView(),

        ]);
    }
}
