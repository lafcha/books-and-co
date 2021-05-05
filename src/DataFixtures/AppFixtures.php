<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\UsersBook;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $slugify = new Slugify();
        $faker = Factory::create('fr_FR');
        $encoder = UserPasswordEncoderInterface::class;
        for ($i=0; $i < 100 ; $i++) { 
            $book = new Book();
            $book
                ->setIsbn(mt_rand(1000000000000,9999999999999))
                ->setTitle($faker->words(3, true))
                ->setDescription($faker->sentences(3, true))
                ->setAuthor($faker->name())
                ->setEditor($faker->words(3, true))
                ->setYear($faker->numberBetween(1950,2021))
                ->setSlug($slugify->slugify($book->getTitle()));
            $manager->persist($book);
        }
        for ($i=0; $i < 100 ; $i++) { 
            $user = new User();
            $user
                ->setPseudo($faker->unique()->userName())
                ->setRoles(["ROLE_USER"])
                ->setEmail($faker->email())
                ->setPassword($faker->password())
                ->setAvatar('assets/img/user/base.png')
                ->setCounty($faker->numberBetween(1,95))
                ->setCity($faker->city())
                ->setSlug($slugify->slugify($user->getPseudo()));
            $manager->persist($user);
        }
       

        $manager->flush();
    }
}
