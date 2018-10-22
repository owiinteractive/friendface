<?php

// src/Controller/ProfileController.php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\UserProfile;
use App\Form\UserProfileType;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="profile_view")
     */
    public function viewProfile($id = "1")
    {
        $userId = (int) $id;
        $user = $this->getDoctrine()
                ->getRepository(UserProfile::class)
                ->find($userId);

        $model = array('user' => $user);
        $view = 'profile.html.twig';
        return $this->render($view, $model);
    }

    /**
     * @Route("/register", name="profile_new")
     */
    public function newProfile(Request $request)
    {
        $userProfile = new UserProfile();
        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            $userProfile = $form->getData();
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userProfile);
            $entityManager->flush();

            return $this->redirectToRoute('profile_success');
        }        

        $view = 'register.html.twig';
        $model = array('form' => $form->createView());
        return $this->render($view, $model);
    }

    /**
     * @Route("/success", name="profile_success")
     */
    public function successProfile(Request $request)
    {
        $view = 'success.html.twig';
        $model = array();
        return $this->render($view, $model);
    }
}

?>