<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\BdCollectionRepository;
use App\Repository\BdRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(BdRepository $bdRepository, CategoryRepository $categoryRepository, AuthorRepository $authorRepository, BdCollectionRepository $bdCollectionRepository): Response
    {
        return $this->render('pages/home/homepage.html.twig', [
            'bds' => $bdRepository->getLastBd(),
            'total_bd' => $bdRepository->countBd(),
            'total_lend' => $bdRepository->countBdLend(),
            'total_category' => $categoryRepository->countCategory(),
            'total_author' => $authorRepository->countAuthor(),
            'total_collection' => $bdCollectionRepository->countBdCollection(),
        ]);
    }
}
