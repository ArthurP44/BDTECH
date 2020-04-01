<?php

namespace App\Controller;

use App\Entity\BdCollection;
use App\Form\BdCollectionType;
use App\Repository\BdCollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bd/collection")
 */
class BdCollectionController extends AbstractController
{
    /**
     * @Route("/", name="bd_collection_index", methods={"GET"})
     */
    public function index(BdCollectionRepository $bdCollectionRepository): Response
    {
        return $this->render('bd_collection/index.html.twig', [
            'bd_collections' => $bdCollectionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="bd_collection_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bdCollection = new BdCollection();
        $form = $this->createForm(BdCollectionType::class, $bdCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bdCollection);
            $entityManager->flush();

            return $this->redirectToRoute('bd_collection_index');
        }

        return $this->render('bd_collection/new.html.twig', [
            'bd_collection' => $bdCollection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bd_collection_show", methods={"GET"})
     */
    public function show(BdCollection $bdCollection): Response
    {
        return $this->render('bd_collection/show.html.twig', [
            'bd_collection' => $bdCollection,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bd_collection_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BdCollection $bdCollection): Response
    {
        $form = $this->createForm(BdCollectionType::class, $bdCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bd_collection_index');
        }

        return $this->render('bd_collection/edit.html.twig', [
            'bd_collection' => $bdCollection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bd_collection_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BdCollection $bdCollection): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bdCollection->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bdCollection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bd_collection_index');
    }
}
