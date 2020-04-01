<?php

namespace App\Controller;

use App\Entity\Bd;
use App\Form\BdType;
use App\Repository\BdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bd")
 */
class BdController extends AbstractController
{
    /**
     * @Route("/", name="bd_index", methods={"GET"})
     */
    public function index(BdRepository $bdRepository): Response
    {
        return $this->render('bd/index.html.twig', [
            'bds' => $bdRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="bd_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bd = new Bd();
        $form = $this->createForm(BdType::class, $bd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bd);
            $entityManager->flush();

            return $this->redirectToRoute('bd_index');
        }

        return $this->render('bd/new.html.twig', [
            'bd' => $bd,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bd_show", methods={"GET"})
     */
    public function show(Bd $bd): Response
    {
        return $this->render('bd/show.html.twig', [
            'bd' => $bd,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bd_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bd $bd): Response
    {
        $form = $this->createForm(BdType::class, $bd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bd_index');
        }

        return $this->render('bd/edit.html.twig', [
            'bd' => $bd,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bd_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Bd $bd): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bd->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bd);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bd_index');
    }
}
