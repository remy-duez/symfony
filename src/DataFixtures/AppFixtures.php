<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\PostLike;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder, UserRepository $userRepository){
        $this->encoder = $encoder;
        $this->repository = $userRepository;
    }
    public function load(ObjectManager $manager)
    {
        //we initiate faker
        $faker = Factory::Create();

        //we create an array that will be used to store users
        $users = [];

        //we create a user
        $user = new User;
        $user->setEmail("remz.duze@mail.com")
        ->setRoles(["ROLE_ADMIN"]);
        $password = $this->encoder->hashPassword($user, '123456admin');
        $user->setPassword($password);

        $manager->persist($user);

        $users[] = $user;

        //we generate a set of users
        for($i=0; $i<10; $i++){
            $user= new User();
            $user->setEmail($faker->email)
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->encoder->hashPassword($user, 'password'));
            $manager->persist($user);
            $users[] = $user;
        }
        
        //we generate a set of category, posts, comments and likes
        for($i=0; $i<=10; $i++){
            $category = new Category();
            $category->setName($faker->words(1, true));
            $manager->persist($category);
            for($j=0; $j< mt_rand(1,15); $j++){
                $post = new Post;
                $post->setTitle($faker->words(3, true))
                ->setContent($faker->text(1500))
                ->setCreatedAt($faker->dateTimeBetween("-6 months", "now"))
                ->setCategory($category)
                ->setAuthor($users[0]);
                $manager->persist($post);
                for($k=0; $k<mt_rand(0,5); $k++){
                    $comment = new Comment();
                    $comment->setContent($faker->text(200))
                    ->setCreatedAt($faker->dateTimeBetween($post->getcreatedAt(), 'now'))
                    ->setPost($post)
                    ->setAuthor($faker->randomElement($users));
                    $manager->persist($comment);
                }
                for($l=0; $l<mt_rand(0,10); $l++){
                    $like = new PostLike();
                    $like->setUser($faker->randomElement($users))
                    ->setPost($post);
                    $manager->persist($like);
                }
            }
        }
        $manager->flush();
    }
}

