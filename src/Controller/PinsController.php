<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use ContainerTT4DlJG\getTwig_Runtime_HttpkernelService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pinscreate", name="app_pins_create", methods={"GET","POST"})
     */
    public function create(Request $req, EntityManagerInterface $em): Response {
        $pin = new Pin();
        $form = $this->createForm(PinType::class, $pin);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($pin);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('pins/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods={"GET"})
     */
    public function show(Pin $pin): Response {

        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET", "PUT"})
     */
    public function edit(Pin $pin, Request $req, EntityManagerInterface $em): Response {
        $form = $this->createForm(PinType::class, $pin, [
            'method'=>'PUT'
        ]);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/delete", name="app_pins_delete", methods={"DELETE"})
     */
    public function delete(Request $req, Pin $pin, EntityManagerInterface $em): Response {

        if ($this->isCsrfTokenValid('pin-deletion_'.$pin->getId(), $req->request->get('csrf_token'))) {
            $em->remove($pin);
            $em->flush();
            $this->addFlash('error', 'Pin successefully deleted');

        }
        return $this->redirectToRoute('app_home');

    }

}
