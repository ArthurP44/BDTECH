<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAllWithBd(),
        ]);
    }

    //embed controller in navbar
    public function listCategories(CategoryRepository $repository)
    {
        return $this->render('category/_list.html.twig', [
            'categories' => $repository->findAll()
        ]);

    }

    /**
     * @Route("/admin/category/new", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $category->setSlug($slugify->generate($category->getName()));
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Une nouvelle catégorie a été ajoutée.');

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/category/{slug}", name="category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/admin/category/{slug}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category, Slugify $slugify): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug($slugify->generate($category->getName()));

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('warning', 'La catégorie a été modifiée avec succès.');

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/{slug}", name="category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        $this->addFlash('danger', 'La catégorie a été supprimée avec succès.');

        return $this->redirectToRoute('category_index');
    }
}
