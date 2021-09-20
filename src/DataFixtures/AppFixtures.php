<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder){
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        //we initiate faker
        $faker = Factory::Create();

        //we create a user
        $user = new User;
        $user->setEmail("remz.duze@mail.com")
        ->setRoles(["ROLE_ADMIN"]);
        $password = $this->encoder->hashPassword($user, '123456admin');
        $user->setPassword($password);

        $manager->persist($user);

        for($i=0; $i<=10; $i++){
            $category = new Category();
            $category->setName($faker->words(1, true));
            $manager->persist($category);
            for($j=0; $j<=10; $j++){
                $post = new Post;
                $post->setTitle($faker->words(3, true))
                ->setContent($faker->text(1500))
                ->setCreatedAt($faker->dateTimeBetween("-6 months", "now"))
                ->setCategory($category)
                ->setAuthor($user);
                $manager->persist($post);
            }
        }
        $manager->flush();
    }
}

