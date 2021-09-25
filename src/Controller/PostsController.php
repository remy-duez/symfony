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

        //we use the paginator bundle to be able to have maximum 12 posts on page and generate other pages
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
        //if the user is connected we get it
        $user = $this->getUser();

        //then we handle the request form the comment type form present in the post page
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        // if it's submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {

            //we initiate a new comment object and hydrates it with content.
            $comment = new Comment();
            $content = $form->get('content')->getData();
            $comment->setAuthor($user)
            ->setPost($post)
            ->setContent($content)
            ->setCreatedAt(new DateTime('now'));

            //it is then persisted and flushed into database using entity interface manager
            $manager->persist($comment);
            $manager->flush();

            //we render a success message and redirect to the same page for the new comment to be visible.
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

        //if the user is connected we get it.
        $user = $this->getUser();

        //if there is no user logged in we return a json that will be caught by javascript on front and display an error message
        if(!$user){
            return $this->json([
                'code' => 403,
                'message' => "You must be logged in to like a post"
            ],403);
        };

        //we use this isLikedByuser method to define if it's already been liked
        if($post->isLikedByUser($user)){

            //the post and the user are used to catch the like property
            $like = $postLikeRepository->findOneBy([
                'post' => $post,
                'user' => $user
            ]);

            //if we are in this loop it means that the post is already liked 
            //so, as this method is called whenever the like button on front is clicked
            //the like is removed from the article
            $manager->remove($like);
            $manager->flush();

            //we return json response for the front end to catch it in javascript
            return $this->json([
                'code' => 200,
                'message' => "like deleted",
                'Likes' => $postLikeRepository->count(['post' => $post])
            ],200);
        } else {

            //if we are in this part of the loop it means that the post was not already liked
            //so we do the opposite , we set a new like.
            $like = new PostLike();
            $like->setPost($post)
                 ->setUser($user);

            $manager->persist($like);
            $manager->flush();
        }
        
        //we return json response for the front end to catch it in javascript
        return $this->json([
            'code' => 200,
            'message' => "like posted",
            'Likes' => $postLikeRepository->count(['post' => $post])
        ],200);
    }
}
