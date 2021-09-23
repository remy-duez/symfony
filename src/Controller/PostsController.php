<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\PostLike;
use App\Form\CommentType;
use App\Repository\PostLikeRepository;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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
    public function showPost(Request $request, Post $post, EntityManagerInterface $manager) : Response
    {

        $user = $this->getUser();

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = new Comment();
            $content = $form->get('content')->getData();
            $comment->setAuthor($user)
            ->setPost($post)
            ->setContent($content)
            ->setCreatedAt(new DateTime('now'));

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('message', 'This comment has been posted');
            return $this->redirectToRoute('show_post',['id' => $post->getId()]);
        }

        return $this->render('posts/show_post.html.twig', [
            'post' => $post,
            'commentForm' => $form->createView()
        ]);
    }

    /**
     * @Route("posts/{id<\d+>}/like", name="post_like")
     */
    public function like(Post $post, PostLikeRepository $postLikeRepository, EntityManagerInterface $manager):Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->json([
                'code' => 403,
                'message' => "You must be logged in to like a post"
            ],403);
        };

        if($post->isLikedByUser($user)){
            $like = $postLikeRepository->findOneBy([
                'post' => $post,
                'user' => $user
            ]);
            $manager->remove($like);
            $manager->flush();
            return $this->json([
                'code' => 200,
                'message' => "like deleted",
                'Likes' => $postLikeRepository->count(['post' => $post])
            ],200);
        } else {
            $like = new PostLike();
            $like->setPost($post)
                 ->setUser($user);

            $manager->persist($like);
            $manager->flush();
        }
        return $this->json([
            'code' => 200,
            'message' => "like posted",
            'Likes' => $postLikeRepository->count(['post' => $post])
        ],200);
    }
}
