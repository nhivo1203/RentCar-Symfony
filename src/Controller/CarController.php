<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\AddCarType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    /**
     * @Route("/car", name="add_car")
     */
    public function createCar(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $car = new Car();

        $form = $this->createForm(AddCarType::class, $car);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $car = $form->getData();
            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('car_list_all');
        }

        return $this->renderForm('car/add_car.html.twig', [
            'form' => $form,
        ]);    }

    /**
     * @Route("/car/all", name="car_list_all")
     */
    public function showCarAll(ManagerRegistry $doctrine): Response
    {
        $cars = $doctrine->getRepository(Car::class)->findAll();

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
    public function showCarDetails(ManagerRegistry $doctrine, int $id): Response
    {
        $car = $doctrine->getRepository(Car::class)->find($id);

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
    public function updateCar(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $car = $entityManager->getRepository(Car::class)->find($id);

        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for id ' . $id
            );
        }

        $car->setName('BMW');
        $entityManager->flush();

        return $this->redirectToRoute('car_show_details', [
            'id' => $car->getId(),
        ]);
    }

    /**
     * @Route("/car/delete/{id}", name="car_delete")
     */
    public function deleteCar(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $car = $entityManager->getRepository(Car::class)->find($id);

        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for id ' . $id
            );
        }

        $entityManager->remove($car);
        $entityManager->flush();

        return new Response('Deleted Car: ' . $car->getName());
    }
}
