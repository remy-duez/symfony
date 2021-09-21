<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function index(PostRepository $postRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('categories/categories.html.twig', [
            'posts' => $postRepository->findAll()
        ]);
    }
}
