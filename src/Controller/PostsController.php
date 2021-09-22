<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    /**
     * @Route("/posts", name="posts")
     */
    public function index(Request $request, PaginatorInterface $paginator, PostRepository $postRepository): Response
    {
        $data = $postRepository->findBy([],['createdAt' => 'desc']);
        $posts = $paginator->paginate($data, $request->query->getInt('page', 1), 12);
        return $this->render('posts/posts.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/posts/{id<\d+>}", name="show_post")
     */
    public function showArticle(Post $post) : Response
    {
        return $this->render('posts/show_post.html.twig', compact('post'));
    }
}
