<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $categories = ['Déjeuner', 'Dîner', 'Petit déjeuner', 'Matériel informatique'];

        foreach($categories as $name) {
            $category = (new Category())
                ->setName($name)
                ->setCreatedAt(\DateTimeImmutable::createFromMutable(new \DateTime()))
                ->setUpdatedAt(new \DateTimeImmutable());
            $manager->persist($category);
            $this->addReference($name, $category);
        }

        $manager->flush();
    }
}
