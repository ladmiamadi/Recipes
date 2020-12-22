<?php
namespace App\Controller\Admin;

use App\Entity\Quantity;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRecipeController extends AbstractController
{    
    /**
     * repository
     *
     * @var RecipeRepository
     */
    private $repository;    
    /**
     * em
     *
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(RecipeRepository $repository, EntityManagerInterface $em)
    {
        $this->repository=$repository;
        $this->em= $em;
        
    }
    
    /**
     * new
     * @Route("/new", name="add_recipe")
     *
     * @param  Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
      

        $recipe= new Recipe();
    
        $form=$this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
            {
                $this->em->persist($recipe);
                $this->em->flush();
                $this->addFlash('success', 'Recette ajoutée avec success');
                return $this->redirectToRoute('home');
            }
        return $this->render('admin/new.html.twig', [
            'recipe'=>$recipe,
            'form'=>$form->createView()
        ]);
    }

}

?>