<?php

namespace App\Controller;

use App\Entity\Bd;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\BdRepository;
use App\Repository\CategoryRepository;
use App\Service\Slugify;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    /**
     * @var CategoryRepository
     */
    private $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/category", name="category_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $paginator->paginate(
            $this->repository->findAllWithBd(),
            $request->query->getInt('page', 1),
            40
        );
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    //embed controller in navbar
    public function listCategories()
    {
        return $this->render('category/_list.html.twig', [
            'categories' => $this->repository->countCategory()
        ]);

    }

    /**
     * @Route("/admin/category/new", name="category_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
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
    public function show(string $slug, PaginatorInterface $paginator, Request $request, BdRepository $bdRepository): Response
    {
        $category = $this->repository->findOneBy(['name' => mb_strtolower($slug)]);

        $bds = $paginator->paginate(
            $bdRepository->findBy(['category' => $category], ['collection' => 'ASC', 'creation_date' => 'ASC']),
            $request->query->getInt('page', 1),
            24
        );

        return $this->render('category/show.html.twig', [
            'bds' => $bds,
        ]);
    }

    /**
     * @Route("/admin/category/{slug}/edit", name="category_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
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
