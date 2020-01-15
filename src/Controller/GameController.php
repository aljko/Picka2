<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Repository\PersonnageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{

    /**
     * @Route("/game", name="game")
     */
    public function index(PersonnageRepository $personnageRepository, ItemRepository $itemRepository)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_register');
        }

        $personnage = $personnageRepository->findOneBy(['user'=>$user]);
        $accesItem = $personnage->getAccess();
        setcookie('accessItem', false, strtotime('now + 1 hour'),'/');
        setcookie('useItem', 0, strtotime('now + 1 hour'),'/');

        if ($accesItem == 1) {
            setcookie('accessItem', true, strtotime('now + 1 hour'),'/');
            $items = $itemRepository->findAll();
        } else {
            $items = '';
        }

        return $this->render('game/index.html.twig', [
            'items' => $items,
        ]);
    }

    /**
     * @Route("/start", name="start")
     */
    public function start()
    {
        $positionX = 460;
        $positionY = 355;

        setcookie('positionX', $positionX, strtotime('now + 1 hour'),'/');
        setcookie('positionY', $positionY, strtotime('now + 1 hour'),'/');

        return $this->redirectToRoute('game');
    }

    /**
     * @Route("/game/wild", name="game_wild")
     */
    public function wild(Request $request)
    {
        $positionX = 460;
        $positionY = 355;

        setcookie('positionX', $positionX, strtotime('now + 1 hour'),'/');
        setcookie('positionY', $positionY, strtotime('now + 1 hour'),'/');

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/game/chalet", name="game_chalet")
     */
    public function chalet(Request $request, PersonnageRepository $personnageRepository)
    {
        $positionX = 886;
        $positionY = 73;

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $personnage = $personnageRepository->findOneBy(['user'=>$user]);
        $accesItem = $personnage->getAccess();

        if ($accesItem == 0) {
            $entityManager = $this->getDoctrine()->getManager();
            $personnage->setAccess(1);
            $entityManager->persist($personnage);
            $entityManager->flush();
        }

        setcookie('positionX', $positionX, strtotime('now + 1 hour'),'/');
        setcookie('positionY', $positionY, strtotime('now + 1 hour'),'/');
        setcookie('itemsGet', true, strtotime('now + 8 seconds'),'/');

        return $this->redirectToRoute('game');
    }

    /**
     * @Route("/game/whitepigeon", name="game_whitepigeon")
     */
    public function whitePigeon(Request $request)
    {
        $positionX = 553;
        $positionY = 79;

        setcookie('positionX', $positionX, strtotime('now + 1 hour'),'/');
        setcookie('positionY', $positionY, strtotime('now + 1 hour'),'/');

        return $this->redirectToRoute('game');
    }

    /**
     * @Route("/game/thinkfap", name="game_thinkfap")
     */
    public function thinkFap(Request $request)
    {
        $positionX = 775;
        $positionY = 355;

        setcookie('positionX', $positionX, strtotime('now + 1 hour'),'/');
        setcookie('positionY', $positionY, strtotime('now + 1 hour'),'/');

        return $this->redirectToRoute('game');
    }

    /**
     * @Route("/game/ghostdistrict", name="game_ghostdistrict")
     */
    public function ghostDistrict(Request $request)
    {
        $positionX = 250;
        $positionY = 64;

        setcookie('positionX', $positionX, strtotime('now + 1 hour'),'/');
        setcookie('positionY', $positionY, strtotime('now + 1 hour'),'/');

        return $this->redirectToRoute('game');
    }

    /**
     * @Route("/game/item/{id}", name="game_item")
     */
    public function useItem(Item $item, ItemRepository $itemRepository,PersonnageRepository $personnageRepository)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $userItems = $user->getItems()->toArray();

        if (!in_array($item, $userItems)) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->addItem($item);
            $entityManager->persist($item);
            $entityManager->flush();
        }

        $victory = $item->getVictory();
        $entityManager = $this->getDoctrine()->getManager();

        $personnage = $personnageRepository->findOneBy(['user'=>$user]);
        $personnage->setStage($victory);

        $entityManager->persist($personnage);
        $entityManager->flush();

        setcookie('ObjectId', '', strtotime('now - 1 hour'),'/');
        setcookie('ObjectDescription', '', strtotime('now - 1 hour'),'/');
        setcookie('ObjectVictory', '', strtotime('now - 1 hour'),'/');
        setcookie('ObjectImage',  '', strtotime('now - 1 hour'),'/');

        return $this->redirectToRoute('game');

 //       return $this->redirectToRoute('game');
    }

    /**
     * @Route("/game/zero", name="game_zero")
     */
    public function setZero(PersonnageRepository $personnageRepository)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $personnage = $personnageRepository->findOneBy(['user'=>$user]);
        $entityManager = $this->getDoctrine()->getManager();
        $personnage->setAccess(0);
        $entityManager->persist($personnage);
        $entityManager->flush();

        return $this->redirectToRoute('game');
    }
}
