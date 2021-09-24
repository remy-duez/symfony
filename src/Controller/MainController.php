<?php

namespace App\Controller;

use App\Repository\PostLikeRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findLastFour();
        $most_liked = $postRepository->findMostLiked();
        return $this->render('main/home.html.twig', [
            'posts' => $posts,
            'liked' => $most_liked
        ]);
    }
}
