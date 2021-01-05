<?php

namespace App\Controller;

use App\Form\EditProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProfileController extends AbstractController
{
    /**
     * @Route("/user/profile", name="user_profile")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }
    /**
     * @Route("/user/recipes", name="user_recipes")
     */
    public function myRecipes(): Response
    {
        return $this->render('user/myRecipes.html.twig');
    }
    /**
     * @Route("/user/account", name="user_account")
     */
    public function myProfile(): Response
    {
        return $this->render('user/myProfile.html.twig');
    }
    /**
     * @Route("/user/edit", name="edit_profile")
     */
    public function editProfile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('message', 'Votre profil a été mis à jour');
            return $this->redirectToRoute('user_account');
        }


        return $this->render('user/editProfile.html.twig', [

            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/user/password", name="edit_password")
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $passEncoder): Response
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            if ($request->request->get('password') == $request->request->get('password2')) {

                $user->setPassword($passEncoder->encodePassword($user, $request->request->get('password')));
                $em->flush();
                $this->addFlash('message', 'Votre mot de passe a été modifié avec succés');
                return $this->redirectToRoute('user_account');
            } else {
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques!');
            }
        }


        return $this->render('user/editPassword.html.twig');
    }
}
