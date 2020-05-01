<?php

namespace App\Controller;

use App\Entity\WishList;
use App\Form\WishListType;
use App\Repository\WishListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/wish/list")
 */
class WishListController extends AbstractController
{
    /**
     * @Route("/", name="wish_list_index", methods={"GET"})
     */
    public function index(WishListRepository $wishListRepository): Response
    {
        return $this->render('wish_list/index.html.twig', [
            'wish_lists' => $wishListRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="wish_list_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $wishList = new WishList();
        $form = $this->createForm(WishListType::class, $wishList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($wishList);
            $entityManager->flush();

            return $this->redirectToRoute('wish_list_index');
        }

        return $this->render('wish_list/new.html.twig', [
            'wish_list' => $wishList,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="wish_list_show", methods={"GET"})
//     */
//    public function show(WishList $wishList): Response
//    {
//        return $this->render('wish_list/show.html.twig', [
//            'wish_list' => $wishList,
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="wish_list_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, WishList $wishList): Response
    {
        $form = $this->createForm(WishListType::class, $wishList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('wish_list_index');
        }

        return $this->render('wish_list/edit.html.twig', [
            'wish_list' => $wishList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="wish_list_delete", methods={"DELETE"})
     */
    public function delete(Request $request, WishList $wishList): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wishList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($wishList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('wish_list_index');
    }
}
