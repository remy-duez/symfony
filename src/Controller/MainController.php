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
        // we get the last four posts in order to display them
        $posts = $postRepository->findLastFour();

        //we get the 4 most liked posts in order to display them
        $most_liked = $postRepository->findFourMostLiked();

        
        return $this->render('main/home.html.twig', [
            'posts' => $posts,
            'liked' => $most_liked
        ]);
    }
}
