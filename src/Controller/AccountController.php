<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangeInformationsType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function index(Request $req, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();


        }
        return $this->render('account/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/moncompte", name="app_account")
     */
    public function account() {
        return $this->render('account/account.html.twig');
    }

    /**
     * @Route("/moncompte/modification", name="app_modif")
     */
    public function modif(Request $req, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder) {
        $notification = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangeInformationsType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $old_password = $form->get('old_password')->getData();
            if ($encoder->isPasswordValid($user, $old_password)) {
                $new_password = $form->get('new_password')->getData();
                $password = $encoder->encodePassword($user, $new_password);
                $user->setPassword($password);
                $em->flush();
                $notification = 'Vous avez modifié votre mot de passe';
            } else {
                $notification = 'Votre ancien mot de passe est invalide';
            }
        }
        return $this->render('account/modif.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
