<?php

namespace App\Controller;

use DateTime;
use App\Entity\Bd;
use App\Form\BdType;
use App\Repository\BdRepository;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BdController extends AbstractController
{
    /**
     * @Route("/admin/bd", name="bd_index", methods={"GET"})
     */
    public function index(BdRepository $bdRepository): Response
    {
        return $this->render('bd/index.html.twig', [
            'bds' => $bdRepository->findAllWithAuthorAndCategory(),
        ]);
    }

    /**
     * @Route("/admin/bd/new", name="bd_new", methods={"GET","POST"})
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        $bd = new Bd();
        $currentTime = new DateTime('now');

        $form = $this->createForm(BdType::class, $bd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bd->setSlug($slugify->generate($bd->getTitle()));
            $bd->setCreatedAt($currentTime);
            $bd->setUpdatedAt($currentTime);
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
     * @Route("bd/{slug}", name="bd_show", methods={"GET"})
     */
    public function show(Bd $bd): Response
    {
        return $this->render('bd/show.html.twig', [
            'bd' => $bd,
        ]);
    }

    /**
     * @Route("/admin/bd/{slug}/edit", name="bd_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bd $bd, Slugify $slugify): Response
    {
        $form = $this->createForm(BdType::class, $bd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bd->setSlug($slugify->generate($bd->getTitle()));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bd_index');
        }

        return $this->render('bd/edit.html.twig', [
            'bd' => $bd,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/bd/{slug}", name="bd_delete", methods={"DELETE"})
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
