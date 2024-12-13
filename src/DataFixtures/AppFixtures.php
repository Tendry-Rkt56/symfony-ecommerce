<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher, private SluggerInterface $slugger)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        
        $faker = Factory::create('fr_FR');

        $user = new User();
        $user->setUsername("Admin 01")
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail('admin@gmail.com')
            ->setSlug($this->slugger->slug('admin 01'))
            ->setPassword($this->hasher->hashPassword($user, '0000'))
            ->setImage(null)
            ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
            ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
        $manager->persist($user);
        $manager->flush();

        for ($i = 1; $i < 10; $i++) {
            $username = $faker->userName();
            $user = (new User());
            /** @var User $user */
            $user->setUsername($username)
                ->setEmail($faker->email())
                ->setAdress($faker->address())
                ->setSlug($this->slugger->slug($username))
                ->setPassword($this->hasher->hashPassword($user, 'password'))
                ->setRoles([])
                ->setImage(null)
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
