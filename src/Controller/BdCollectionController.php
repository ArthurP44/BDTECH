<?php

namespace App\Controller;

use App\Entity\Bd;
use App\Entity\BdCollection;
use App\Form\BdCollectionType;
use App\Repository\BdCollectionRepository;
use App\Service\Slugify;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BdCollectionController extends AbstractController
{
    /**
     * @var BdCollectionRepository
     */
    private $repository;

    public function __construct(BdCollectionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/collection", name="bd_collection_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $bdCollections = $paginator->paginate(
            $this->repository->findAllWithBd(),
            $request->query->getInt('page', 1),
            40
        );
        return $this->render('bd_collection/index.html.twig', [
            'bd_collections' => $bdCollections,
        ]);
    }

    //embed controller in navbar
    public function listBdCollections()
    {
        return $this->render('bd_collection/_list.html.twig', [
            'BdCollections' => $this->repository->countBdCollection()
        ]);

    }

    /**
     * @Route("/admin/collection/new", name="bd_collection_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        $bdCollection = new BdCollection();
        $form = $this->createForm(BdCollectionType::class, $bdCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bdCollection->setSlug($slugify->generate($bdCollection->getName()));
            $entityManager->persist($bdCollection);
            $entityManager->flush();

            $this->addFlash('success', 'Une série a été ajoutée avec succès.');

            return $this->redirectToRoute('bd_collection_index');
        }

        return $this->render('bd_collection/new.html.twig', [
            'bd_collection' => $bdCollection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("collection/{slug}", name="bd_collection_show", methods={"GET"})
     */
    public function show(string $slug, PaginatorInterface $paginator, Request $request)
    {
        $bd_collection = $this->repository->findOneBy(['slug' => mb_strtolower($slug)]);

        $bds = $paginator->paginate(
            $this->getDoctrine()
                ->getRepository(Bd::class)
                ->findBy(['collection' => $bd_collection], ['creation_date' => 'ASC']),
            $request->query->getInt('page', 1),
            24
        );

        return $this->render('bd_collection/show.html.twig', [
            'bds' => $bds,
        ]);
    }

    /**
     * @Route("/admin/collection/{slug}/edit", name="bd_collection_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, BdCollection $bdCollection, Slugify $slugify): Response
    {
        $form = $this->createForm(BdCollectionType::class, $bdCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bdCollection->setSlug($slugify->generate($bdCollection->getName()));
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('warning', 'La série a été modifée avec succès.');

            return $this->redirectToRoute('bd_collection_index');
        }

        return $this->render('bd_collection/edit.html.twig', [
            'bd_collection' => $bdCollection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/collection/{slug}", name="bd_collection_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, BdCollection $bdCollection): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bdCollection->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bdCollection);
            $entityManager->flush();
        }

        $this->addFlash('danger', 'La série a été supprimée avec succès.');

        return $this->redirectToRoute('bd_collection_index');
    }
}
