<?php

namespace App\Controller;

use App\Entity\Apartment;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RoomController extends AbstractController
{
    private EntityManagerInterface $manager;
    private RoomRepository $roomRepository;
    private SerializerInterface $serializer;

    public function __construct(
        EntityManagerInterface $manager,
        RoomRepository $roomRepository,
        SerializerInterface $serializer
    )
    {
        $this->manager = $manager;
        $this->roomRepository = $roomRepository;
        $this->serializer = $serializer;
    }
    /**
     * @Route("/apartment/{apartmentId}/addRoom", name="create_room",  methods={"POST"})
     */
    public function createRoom(Request $request, int $apartmentId): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        $room = new Room();

        $apartment = $this->manager->getRepository(Apartment::class)->find($apartmentId);

        if($apartment === null) {
            return $this->json(['error' => 'Not Found'], '404');
        }
        $room->setApartment($apartment);
        $room->setNumber($payload['number']);
        $room->setArea($payload['area']);
        $room->setPrice($payload['price']);
        $room->setImage($payload['image']);

        $this->manager->persist($room);
        $this->manager->flush();

        $normalizedRoom = $this->serializer->normalize($room, null, [
                AbstractNormalizer::GROUPS => ['room']
        ]);
        return $this->json($normalizedRoom);
    }

    /**
     * @Route("/hello", name="hello_world",  methods={"GET"})
     */
    public function helloWorld(): JsonResponse
    {

        return $this->json('hello');
    }


    /**
     * @Route("/apartment/{apartmentId}/rooms", name="get_all_room_apartment",  methods={"GET"})
     */
    public function getAllRoomInApartment(int $apartmentId): JsonResponse
    {
        $apartment = $this->manager->getRepository(Apartment::class)->find($apartmentId);

        if($apartment === null) {
            return $this->json(['error' => 'Not Found'], '404');
        }
        $rooms = $this->roomRepository->findRoomByApartment($apartment);

        $normalizedRoom = $this->serializer->normalize($rooms, null, [
            AbstractNormalizer::GROUPS => ['room']
        ]);
        return $this->json($normalizedRoom);
    }

    /**
     * @Route("/rooms", name="get_all_room",  methods={"GET"})
     */
    public function getAllRoom(): JsonResponse
    {
        $rooms = $this->manager->getRepository(Room::class)->findAll();

        $normalizedRoom = $this->serializer->normalize($rooms, null, [
            AbstractNormalizer::GROUPS => ['room']
        ]);
        return $this->json($normalizedRoom);
    }

//    /**
//     * @Route("/apartment/{apartmentId}/room/{roomId}", name="get_room",  methods={"GET"})
//     */
//    public function getOneRoom(int $apartmentId,int $roomId): JsonResponse
//    {
//        $room = $this->roomRepository->findOneRoomByApartment($apartmentId, $roomId);
//        $normalizedRoom = $this->serializer->normalize($room, null, [
//            AbstractNormalizer::GROUPS => ['room']
//        ]);
//        return $this->json($normalizedRoom);
//    }

    /**
     * @Route("/room/{roomId}", name="get_room",  methods={"GET"})
     */
    public function getOneRoom(int $roomId): JsonResponse
    {
        $room = $this->manager->getRepository(Room::class)->find($roomId);
        $normalizedRoom = $this->serializer->normalize($room, null, [
            AbstractNormalizer::GROUPS => ['room']
        ]);
        return $this->json($normalizedRoom);
    }


    /**
     * @Route("/editRoom/{roomId}", name="update_room",  methods={"PATCH"})
     */
    public function updateRoom(int $roomId, Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);

        /** @var Room $room */
        $room = $this->manager->getRepository(Room::class)->find($roomId);

        $room->setNumber($payload['number']);
        $room->setArea($payload['area']);
        $room->setPrice($payload['price']);
        $room->setImage($payload['image']);

        $this->manager->persist($room);
        $this->manager->flush();

        $normalizedRoom = $this->serializer->normalize($room, null, [
            AbstractNormalizer::GROUPS => ['room']
        ]);
        return $this->json($normalizedRoom);
    }

    /**
     * @Route("/deleteRoom/{roomId}", name="delete_room",  methods={"DELETE"})
     */
    public function deleteRoom(int $roomId): JsonResponse
    {
        $room = $this->manager->getRepository(Room::class)->find($roomId);
        $this->manager->remove($room);
        $this->manager->flush();

        $normalizedRoom = $this->serializer->normalize($room, null, [
            AbstractNormalizer::GROUPS => ['room']
        ]);
        return $this->json($normalizedRoom);
    }

    /**
     * @Route("/room/{roomId}/addReservation", name="create_reservation",  methods={"POST"})
     */
    public function createReservation(Request  $request, int $roomId): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        $reservation = new Reservation();

        $room = $this->manager->getRepository(Room::class)->find($roomId);
        if($room === null) {
            return $this->json(['error' => 'Not Found'], '404');
        }

        $reservation->setRoom($room);
        $reservation->setValue($payload['value']);

        $this->manager->persist($reservation);
        $this->manager->flush();

        $normalizedReservation = $this->serializer->normalize($reservation, null, [
            AbstractNormalizer::GROUPS => ['reservation']
        ]);
        return $this->json($normalizedReservation);
    }

    /**
     * @Route("/reservations", name="get_all_reservation",  methods={"GET"})
     */
    public function getAllReservation(): JsonResponse
    {
        $reservations = $this->manager->getRepository(Reservation::class)->findAll();
        $normalizedReservation = $this->serializer->normalize($reservations, null, [
            AbstractNormalizer::GROUPS => ['reservation']
        ]);
        return $this->json($normalizedReservation);
    }

    /**
     * @Route("/deleteReservation/{reservationId}", name="delete_reservation",  methods={"DELETE"})
     */
    public function deleteReservation(int $reservationId): JsonResponse
    {
        $reservation = $this->manager->getRepository(Reservation::class)->find($reservationId);
        $this->manager->remove($reservation);
        $this->manager->flush();

        $normalizedReservation = $this->serializer->normalize($reservation, null, [
            AbstractNormalizer::GROUPS => ['reservation']
        ]);
        return $this->json($normalizedReservation);
    }

}
