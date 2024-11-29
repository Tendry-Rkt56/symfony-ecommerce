<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        
        $faker = Factory::create('fr_FR');

        $user = new User();
        $user->setUsername("Tendry Rkt")
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail('tendry@gmail.com')
            ->setPassword($this->hasher->hashPassword($user, '0000'))
            ->setImage(null)
            ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
            ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
        $manager->persist($user);
        $manager->flush();

        for ($i = 1; $i < 10; $i++) {
            $user = (new User());
            $user->setUsername($faker->name())
                ->setEmail($faker->email())
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
