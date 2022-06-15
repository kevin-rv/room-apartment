<?php

namespace App\Controller;

use App\Entity\Room;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController
{
    /**
     * @Route("/addRoom", name="create_room",  methods={"POST"})
     */
    public function createRoom(ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $room = new Room();
        $room->setNumber('150');
        $room->setArea('10,5');
        $entityManager->persist($room);
        $entityManager->flush();
        return $this->json($room);
    }

    /**
     * @Route("/hello", name="hello_world",  methods={"GET"})
     */
    public function helloWorld(): JsonResponse
    {

        return $this->json('hello');
    }


    /**
     * @Route("/rooms", name="get_all_room",  methods={"GET"})
     */
    public function getAllRoom(ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $rooms = $entityManager->getRepository(Room::class)->findAll();
        return $this->json($rooms);
    }

    /**
     * @Route("/room/{roomId}", name="get_room",  methods={"GET"})
     */
    public function getOneRoom(ManagerRegistry $doctrine, int $roomId): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $room = $entityManager->getRepository(Room::class)->find($roomId);
        return $this->json($room);
    }

    /**
     * @Route("/edit/{roomId}", name="update_room",  methods={"PATCH"})
     */
    public function updateRoom(ManagerRegistry $doctrine, int $roomId): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $room = $entityManager->getRepository(Room::class)->find($roomId);
        return $this->json($room);
    }

    /**
     * @Route("/delete/{roomId}", name="delete_room",  methods={"DELETE"})
     */
    public function deleteRoom(ManagerRegistry $doctrine, int $roomId): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $room = $entityManager->getRepository(Room::class)->find($roomId);
        $entityManager->remove($room);
        $entityManager->flush();
        return $this->json($room);
    }


}
