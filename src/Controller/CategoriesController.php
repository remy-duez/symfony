<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/categories/{id<\d+>}", name="show_category")
     */
    public function showCategory(CategoryRepository $categoryRepository,PostRepository $postRepository,PaginatorInterface $paginator,Request $request, $id): Response
    {
        $category = $categoryRepository->findOneBy(['id' => $id]);
        $data = $postRepository->findBy(['category' => $category],['createdAt'=> 'desc']);
        $posts = $paginator->paginate($data, $request->query->getInt('page', 1), 8);
        return $this->render('categories/category.html.twig', [
            'id' => $id,
            'posts' => $posts
        ]);
    }
}
