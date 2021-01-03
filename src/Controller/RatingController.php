<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Entity\Recipe;
use App\Form\RatingType;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rating")
 */
class RatingController extends AbstractController
{
    /**
     * @Route("/", name="rating_index", methods={"GET"})
     */
    public function index(RatingRepository $ratingRepository): Response
    {
        return $this->render('rating/index.html.twig', [
            'ratings' => $ratingRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new/ajax", name="rating_ajax", methods={"GET","POST"})
     */
    public function newAjax(Request $request, EntityManagerInterface $em): Response
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



            $vote = $request->request->get('vote');
            $id_recipe = $request->request->get('recipe');


            $entityManager = $this->getDoctrine()->getManager();

            $rating = new Rating();

            $rating->setNote($vote);
            $rating->setRecipe($em->getReference('App\Entity\Recipe', $id_recipe));


            $entityManager->persist($rating);
            $entityManager->flush();
            return new JsonResponse(
                array(
                    'status' => 'OK',
                    'message' => $rating
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
    }

    /**
     * @Route("/new", name="rating_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rating = new Rating();
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rating);
            $entityManager->flush();

            return $this->redirectToRoute('rating_index');
        }

        return $this->render('rating/new.html.twig', [
            'rating' => $rating,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rating_show", methods={"GET"})
     */
    public function show(Rating $rating): Response
    {
        return $this->render('rating/show.html.twig', [
            'rating' => $rating,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rating_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Rating $rating): Response
    {
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rating_index');
        }

        return $this->render('rating/edit.html.twig', [
            'rating' => $rating,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rating_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Rating $rating): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rating->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rating);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rating_index');
    }
}
