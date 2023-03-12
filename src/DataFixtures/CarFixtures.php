<?php

namespace App\DataFixtures;

use App\Entity\Car;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class CarFixtures extends Fixture
{
    private readonly Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('nl_NL');
    }

    public function load(ObjectManager $manager): void
    {
        $i = 0;
        while ($i++ < 50) {
            $car = new Car();
            $car->setName($this->faker->randomElement($this->getCarBrands())." ".$this->faker->words(3, true));
            $car->setPrice($this->faker->numberBetween(10000, 200000));
            $car->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('- 1 year')
                )
            );

            $manager->persist($car);
        }
        $manager->flush();
    }

    private function getCarBrands(): array
    {
        return [
            "Ford",
            "Toyota",
            "Volkswagen",
            "Honda",
            "Nissan",
            "Chevrolet",
            "RAM",
            "Hyundai",
            "Mercedes",
            "Mazda",
            "Kia",
            "Great",
            "Subaru",
            "Renault",
            "Jeep",
            "Skoda",
            "Dacia",
            "Suzuki",
            "Audi",
            "BMW"
        ];
    }
}
