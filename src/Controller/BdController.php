<?php

namespace App\Controller;

use DateTime;
use App\Entity\Bd;
use App\Form\BdType;
use App\Repository\BdRepository;
use App\Service\Slugify;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BdController extends AbstractController
{
    /**
     * @var BDRepository
     */
    private $repository;

    public function __construct(BdRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/bd", name="bd_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(): Response
    {
        return $this->render('bd/index.html.twig', [
            'bds' => $this->repository->findAllWithAuthorAndCategory(),
        ]);
    }

    /**
     * @Route("/admin/bd/new", name="bd_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
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

            $this->addFlash('success', 'Une nouvelle BD a été ajoutée.');

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
            'authors' => $bd->getAuthors(),
        ]);
    }

    /**
     * @Route("/admin/bd/{slug}/edit", name="bd_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Bd $bd, Slugify $slugify): Response
    {
        $form = $this->createForm(BdType::class, $bd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bd->setSlug($slugify->generate($bd->getTitle()));
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('warning', 'La BD a été modifiée avec succès.');

            return $this->redirectToRoute('bd_index');
        }

        return $this->render('bd/edit.html.twig', [
            'bd' => $bd,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/bd/{slug}", name="bd_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Bd $bd): Response
    {
        if ($this->isCsrfTokenValid('delete' . $bd->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bd);
            $entityManager->flush();
        }

        $this->addFlash('danger', 'La BD a été supprimée avec succès.');

        return $this->redirectToRoute('bd_index');
    }

    /**
     * @Route("/list", name="bd_list", methods={"GET"})
     * @return Response
     */
    public function bdList(PaginatorInterface $paginator, Request $request): Response
    {
        $bds = $paginator->paginate(
            $this->repository->findAllforListQuery(),
            $request->query->getInt('page', 1),
            12
            );
        return $this->render('bd/list.html.twig', [
           'bds' => $bds,
        ]);
    }

    /**
     * @Route("/lend", name="bd_lend", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function BdLend(PaginatorInterface $paginator, Request $request): Response
    {
        $bds = $paginator->paginate(
            $this->repository->getLendBd(),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('bd/lend.html.twig', [
            'bds' => $bds,
        ]);
    }


}
