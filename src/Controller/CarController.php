<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\AddCarType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    private CarRepository $carRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(CarRepository $carRepository, EntityManagerInterface $entityManager)
    {
        $this->carRepository = $carRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/car", name="add_car")
     */
    public function createCar(Request $request): Response
    {
        $car = new Car();
        $form = $this->createForm(AddCarType::class, $car);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $car = $form->getData();
            $this->carRepository->add($car, true);
            return $this->redirectToRoute('car_list_all');
        }
        return $this->renderForm('car/add_car.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/car/all", name="car_list_all")
     */
    public function getAllCar(): Response
    {
        $cars = $this->carRepository->findAll();

        if (!$cars) {
            throw $this->createNotFoundException(
                'No cars found'
            );
        }

        return $this->render('car/index.html.twig', ['cars' => $cars]);
    }

    /**
     * @Route("/car/{id}", name="car_show_details")
     */
    public function getCarDetails(int $id): Response
    {
        $car = $this->carRepository->find($id);

        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for id ' . $id
            );
        }

        return new Response('Check out this great car: ' . $car->getName());
    }

    /**
     * @Route("/car/edit/{id}", name="car_edit")
     */
    public function updateCar(int $id): Response
    {
        $car = $this->carRepository->find($id);

        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for id ' . $id
            );
        }

        $car->setName('BMW');
        $this->entityManager->flush();

        return $this->redirectToRoute('car_show_details', [
            'id' => $car->getId(),
        ]);
    }

    /**
     * @Route("/car/delete/{id}", name="car_delete")
     */
    public function deleteCar(int $id): Response
    {
        $car = $this->carRepository->find($id);

        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for id ' . $id
            );
        }

        $this->entityManager->remove($car);
        $this->entityManager->flush();

        return new Response('Deleted Car: ' . $car->getName());
    }
}
