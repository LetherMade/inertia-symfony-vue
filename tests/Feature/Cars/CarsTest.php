<?php
declare(strict_types=1);

use App\Entity\Car;
use App\Repository\CarRepository;

uses()->group('cars');

it('shows a list of cars', function() {
    client()->request('GET', '/');
    $this->assertResponseStatusCodeSame(200);
});

it('it shows cars create form', function() {
    client()->request('GET', '/create');
    $this->assertResponseStatusCodeSame(200);
});

it('fails to create a care when no name is given', function() {
    $car = new Car();
    $car->setPrice(1000);

    $violations = validate($car);
    $this->assertCount(1, $violations);

    $violation = $violations[0];
    $this->assertSame('name', $violation->getPropertyPath());
    $this->assertSame('This value should not be blank.', $violation->getMessage());
});

it('fails to create a care when no or wrong price is given', function() {
    $car = new Car();
    $car->setName('Car name');

    $violations = validate($car);
    $this->assertCount(1, $violations);

    $violation = $violations[0];
    $this->assertSame('price', $violation->getPropertyPath());
    $this->assertSame('This value should not be blank.', $violation->getMessage());
});

it('can create a car entity', function() {
    client()->request('POST', '/create', [
        'name' => 'Car name',
        'price' => 45000
    ]);

    $this->assertResponseStatusCodeSame(302);

    $carRepository = $this->getContainer()->get(CarRepository::class);
    $cars = $carRepository->findAll();
    $this->assertCount(1, $cars);

    $this->assertSame('Car name', $cars[0]->getName());
    $this->assertSame(45000, $cars[0]->getPrice());
});

it('it shows cars edit form', function() {
    createCar();

    $savedCar = test()->carRepository()->findOneBy(['name' => 'Car name']);
    $this->assertInstanceOf(Car::class, $savedCar);

    client()->request('GET', "{$savedCar->getId()}/edit");
    $this->assertResponseStatusCodeSame(200);
});

it('can edit a car', function() {
    $car = createCar();

    client()->request('PUT', "{$car->getId()}/edit", [
        'name' => 'Other name',
        'price' => 4000,
    ]);

    $updatedCar = test()->carRepository()->findOneBy(['name' => 'Other name']);
    $this->assertInstanceOf(Car::class, $updatedCar);

    $this->assertSame('Other name', $updatedCar->getName());
    $this->assertSame(4000, $updatedCar->getPrice());
});

it('can search for cars', function() {
    createCar('Alpha', 1000);
    createCar('Bravo', 2000);
    createCar('Charlie', 3000);

    $crawler = client()->request('GET', '/?search=Alpha');
    $data = json_decode($crawler->filter('[data-page]')->extract(['data-page'])[0], true, 512, JSON_THROW_ON_ERROR)['props']['cars']['data'];
    $this->assertCount(1, $data);
    $this->assertSame('Alpha', $data[0]['name']);

    $crawler = client()->request('GET', '/?minPrice=1200&maxPrice=2200');
    $data = json_decode($crawler->filter('[data-page]')->extract(['data-page'])[0], true, 512, JSON_THROW_ON_ERROR)['props']['cars']['data'];
    $this->assertCount(1, $data);
    $this->assertSame('Bravo', $data[0]['name']);
});

function createCar(string $name = 'Car name', int $price = 45000): Car
{
    $car = new Car();
    $car->setName($name);
    $car->setPrice($price);

    test()->carRepository()->saveAndFlush($car);

    return $car;
}