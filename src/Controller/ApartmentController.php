<?php

namespace App\Controller;

use App\Entity\Apartment;
use App\Repository\ApartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ApartmentController extends AbstractController
{
    private SerializerInterface $serializer;
    private EntityManagerInterface $manager;
    private ApartmentRepository $apartmentRepository;

    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $manager,
        ApartmentRepository $apartmentRepository
    )
    {

        $this->serializer = $serializer;
        $this->manager = $manager;
        $this->apartmentRepository = $apartmentRepository;
    }
    /**
     * @Route("/addApartment", name="create_apartment", methods={"POST"})
     */
    public function createApartment(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);

        $apartment = new Apartment();
        $apartment->setName($payload['name']);
        $apartment->setStreet($payload['street']);
        $apartment->setZipCode($payload['zipcode']);
        $apartment->setCity($payload['city']);
        $apartment->setImage($payload['image']);
        $this->manager->persist($apartment);
        $this->manager->flush();

        return $this->json($apartment);
    }

    /**
     * @Route("/apartments", name="get_all_apartment",  methods={"GET"})
     */
    public function getAllApartment(): JsonResponse
    {
        $apartments = $this->manager->getRepository(Apartment::class)->findAll();
        $normalizedApartment = $this->serializer->normalize($apartments, null, [
            AbstractNormalizer::GROUPS => ['apartment']
        ]);
        return $this->json($normalizedApartment);
    }

    /**
     * @Route("/apartment/{apartmentId}", name="get_apartment",  methods={"GET"})
     */
    public function getOneApartment(ManagerRegistry $doctrine, int $apartmentId): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $apartment = $entityManager->getRepository(Apartment::class)->find($apartmentId);
        return $this->json($apartment);
    }


    /**
     * @Route("/edit/{apartmentId}", name="update_apartment",  methods={"PATCH"})
     */
    public function updateApartment(int $apartmentId, Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);

        $apartment = $this->manager->getRepository(Apartment::class)->find($apartmentId);

        $apartment->setName($payload['name']);
        $apartment->setStreet($payload['street']);
        $apartment->setZipCode($payload['zipcode']);
        $apartment->setCity($payload['city']);
        $apartment->setImage($payload['image']);
        $this->manager->persist($apartment);
        $this->manager->flush();

        $normalizedApartment = $this->serializer->normalize($apartment, null, [
            AbstractNormalizer::GROUPS => ['apartment']
        ]);
        return $this->json($normalizedApartment);

    }


    /**
     * @Route("/delete/{apartmentId}", name="delete_apartment",  methods={"DELETE"})
     */
    public function deleteApartment(ManagerRegistry $doctrine, int $apartmentId): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $apartment = $entityManager->getRepository(Apartment::class)->find($apartmentId);
        $entityManager->remove($apartment);
        $entityManager->flush();
        return $this->json($apartment);
    }
}
