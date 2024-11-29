<?php

namespace App\DataFixtures;

use App\Entity\Product;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture
{

    public function __construct(private SluggerInterface $slugger)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));
        $categories = ['Déjeuner', 'Dîner', 'Petit déjeuner', 'Matériel informatique'];

        for ($i = 1; $i <= 100; $i++) {
            $foodName = $faker->foodName();
            $product = (new Product())
                        ->setName($foodName)
                        ->setPrice($faker->numberBetween(1000, 1000000))
                        ->setSlug($this->slugger->slug($foodName, '-'))
                        ->setCategory($this->getReference($categories[$faker->numberBetween(0, 3)]))
                        ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                        ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
