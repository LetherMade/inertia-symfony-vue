<?php

namespace App\Controller\Cars;

use App\Controller\AbstractController;
use App\Controller\Traits\PaginationTrait;
use App\Entity\Car;
use App\Repository\CarRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\s;

class CarsController extends AbstractController
{
    use PaginationTrait;

    #[Route('/', name: 'cars', methods: ['GET'])]
    public function index(Request $request, CarRepository $carRepository): Response
    {
        $search = $request->get('search');
        $minPrice = $request->get('minPrice');
        $maxPrice = $request->get('maxPrice');
        $page = (int) ($request->get('page') ?? 1);

        [$limit, $offset] = $this->getPaginationLimitAndOffset($page);

        $data = $this->wrapWithPaginationData(
            $request,
            array_map(
                static fn (Car $car) => $car->toArray(),
                $carRepository->findAllMatchingFilter($search, $minPrice, $maxPrice, $limit, $offset),
            ),
            $carRepository->countAllMatchingFilter($search, $minPrice, $maxPrice),
            $page,
            'cars'
        );

        return $this->renderWithInertia('Cars/CarsList', [
            'filters' => ['search' => $search, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice],
            'cars' => $data
        ]);
    }

    #[Route('/create', name: 'cars_create', methods: ['GET'])]
    #[Route('/create', name: 'cars_store', methods: ['POST'])]
    public function create(Request $request, CarRepository $carRepository): Response
    {
        if ($request->getMethod() === 'POST') {
            $errors = $this->handleFormData($request, new Car(), $carRepository, 'Car created!');

            if (count($errors) === 0) {
                return new RedirectResponse($this->generateUrl('cars'));
            }
        }

        return $this->renderWithInertia('Cars/CarsCreate', [
            'errors' => isset($errors) ? new \ArrayObject($errors) : new \ArrayObject()
        ]);
    }

    #[Route('/{id}/edit', name: 'cars_edit', methods: ['GET'])]
    #[Route('/{id}/edit', name: 'cars_update', methods: ['PUT'])]
    public function edit(Request $request, Car $car, CarRepository $carRepository): Response
    {
        if ($request->getMethod() === 'PUT') {
            $errors = $this->handleFormData($request, $car, $carRepository, 'Car updated!');

            if (count($errors) === 0) {
                return new RedirectResponse($this->generateUrl('cars_edit', ['id' => $car->getId()]));
            }
        }

        return $this->renderWithInertia('Cars/CarsEdit', [
            'car' => $car->toArray(),
            'errors' => isset($errors) ? new \ArrayObject($errors) : new \ArrayObject()
        ]);
    }


    protected function handleFormData(Request $request, Car $car, CarRepository $carRepository, string $successMessage): array
    {
        $car->setName($request->get('name'));
        $car->setPrice((int) $request->get('price'));

        $violations = $this->validator->validate($car);

        if ($violations->count() === 0) {
            $carRepository->saveAndFlush($car);

            $this->addFlash('success', $successMessage);

            return [];
        }

        $errors = [];

        foreach ($violations as $violation) {
            $propertyName = (string) s($violation->getPropertyPath())->snake();

            $errors[$propertyName] = (string) $violation->getMessage();
        }

        return $errors;
    }
}
