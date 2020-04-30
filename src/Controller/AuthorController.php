<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Service\Slugify;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @var AuthorRepository
     */
    private $repository;

    public function __construct(AuthorRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/author", name="author_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(AuthorRepository $authorRepository): Response
    {
        return $this->render('author/index.html.twig', [
            'authors' => $authorRepository->findAllWithBd(),
        ]);
    }

    //embed controller in navbar
    public function listAuthors(AuthorRepository $repository)
    {
        return $this->render('author/_list.html.twig', [
            'authors' => $repository->countAuthor()
        ]);

    }

    /**
     * @Route("/admin/author/new", name="author_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author->setSlug($slugify->generate($author->getName()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', 'Un nouvel Auteur a été ajouté avec succès.');

            return $this->redirectToRoute('author_index');
        }

        return $this->render('author/new.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("author/{slug}", name="author_show", methods={"GET"})
     */
    public function show(Request $request, Author $author, PaginatorInterface $paginator): Response
    {

        $bds = $paginator->paginate(
            $this->repository->findAllBdByAuthorQuery($author->getName()),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('author/show.html.twig', [
            'bds' => $bds,
        ]);

    }

    /**
     * @Route("/admin/author/{slug}/edit", name="author_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Author $author, Slugify $slugify): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author->setSlug($slugify->generate($author->getName()));
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('warning', 'L\'auteur a été modifié avec succès.');

            return $this->redirectToRoute('author_index');
        }

        return $this->render('author/edit.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/author/{slug}", name="author_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Author $author): Response
    {
        if ($this->isCsrfTokenValid('delete'.$author->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($author);
            $entityManager->flush();
        }

        $this->addFlash('danger', 'L\'auteur a été supprimé avec succès.');

        return $this->redirectToRoute('author_index');
    }
}
