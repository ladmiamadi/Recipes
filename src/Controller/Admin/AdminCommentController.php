<?php

namespace App\Controller\Admin;


use App\Entity\Comments;
use App\Entity\Recipe;
use App\Form\CommentsType;

use App\Form\RecipeType;
use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminCommentController extends AbstractController
{


    /**
     * @Route("/admin/comment", name="comments_home_admin")
     */
    public function index(Request $request, CommentsRepository $rep): Response
    {

        $comments = $rep->findAll();

        return $this->render('admin/comments/index.html.twig', [
            'comments' => $comments
        ]);
    }
    /**
     * @Route("admin/show-comment-{id}", name="comments_show_admin", methods={"GET","POST"})
     */
    public function show(Comments $comment, Request $request): Response
    {

        return $this->render('admin/comments/show.html.twig', [
            'comment' => $comment

        ]);
    }
    /**
     * @Route("admin/comments/{id}", name="comments_delete_admin", methods={"DELETE"})
     */
    public function delete(Request $request, Comments $comment): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comments_home_admin');
    }
    /**
     * @Route("admin/activer-comment/{id}/", name="comments_active_admin")
     */
    public function activer(Comments $comments, Request $request)
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
        $comments->setIsValidated(($comments->getIsValidated()) ? false : true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($comments);
        $em->flush();
        //return new Response("true");
        return new JsonResponse(
            array(
                'status' => 'success',
                'message' => 1
            ),
            200
        );
    }
}
