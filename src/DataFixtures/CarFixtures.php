<?php

namespace App\DataFixtures;

use App\Entity\Car;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CarFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $mazda = new Car('Mazda CX-5', 4500000, new \DateTimeImmutable());
        $manager->persist($mazda);

        $manager->flush();
    }
}
