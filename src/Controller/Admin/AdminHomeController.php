<?php

namespace App\Controller\Admin;


use App\Entity\Comments;
use App\Entity\Recipe;
use App\Form\CommentsType;

use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminHomeController extends AbstractController
{


    /**
     * @Route("/admin", name="home_admin", methods={"GET","POST"})
     */
    public function index(): Response
    {


        return $this->render('admin/index.html.twig');
    }
}
