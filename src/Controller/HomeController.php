<?php

namespace App\Controller;

use App\Entity\Personnage;
use App\Entity\User;
use App\Repository\ItemRepository;
use App\Repository\PersonnageRepository;
use App\Repository\UserRepository;
use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PersonnageRepository $personnageRepository, ItemRepository $itemRepository)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $personnage = $personnageRepository->findOneBy(['user'=>$user]);
        $personnages = $personnageRepository->findAll();
        $items = $itemRepository->findAll();
        $userItems = $user->getItems();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $user,
            'personnage'=>$personnage,
            'personnages'=>$personnages,
            'user_items' => $userItems,
            'items' => $items,
        ]);
    }

    /**
     * @Route("/selection", name="selection_hero")
     * @param ApiService $apiService
     * @param Request $request
     * @param PersonnageRepository $personnageRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function chooseYourHero(
        ApiService $apiService,
        Request $request,
        PersonnageRepository $personnageRepository
    ) {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_register');
        }

        $personnages = $personnageRepository->findAll();

        $selectedId = [285, 322, 332, 612, 370, 396, 652, 208];
        $heros=[];

        foreach ($selectedId as $value) {
            $hero = $apiService->requestApi($value,'');
            $heros[$hero['id']]=$hero;
        }

        $personnage = $personnageRepository->findOneBy(['user'=>$user]);

        if ($request->isMethod('POST')) {
            if (!$personnage) {
                $personnage = new Personnage;
            }
                $apiId = $request->get('hero');
                $name = $request->get('name');
                $personnage->setIdApi($apiId);
                $personnage->setIntelligence($heros[$apiId]['powerstats']['intelligence']);
                $personnage->setStrength($heros[$apiId]['powerstats']['strength']);
                $personnage->setSpeed($heros[$apiId]['powerstats']['speed']);
                $personnage->setDurability($heros[$apiId]['powerstats']['durability']);
                $personnage->setPower($heros[$apiId]['powerstats']['power']);
                $personnage->setCombat($heros[$apiId]['powerstats']['combat']);
                $personnage->setName($name);
                $personnage->setImage($heros[$apiId]['image']['url']);
                $personnage->setUser($user);
                $personnage->setNameApi($heros[$apiId]['name']);
                $personnage->setAccess(0);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($personnage);
                $entityManager->flush();



            return $this->redirectToRoute('home');
        }
        return $this->render('home/heros.html.twig', [
            'heros'=>$heros,
            'user' => $user,
            'personnage' => $personnage,
            'personnages'=> $personnages,
            ]);
    }

    /**
     * @Route("/caracteristique", name="caracteristique_hero")
     */
    public function viewCaracteristique(PersonnageRepository $personnageRepository)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_register');
        }
        $personnage = $personnageRepository->findOneBy(['user'=>$user]);
        $personnages = $personnageRepository->findAll();

        $apiService = new ApiService;
        $heroPower = $apiService->requestApi($personnage->getIdApi(),'powerstats');
        $heroBiography = $apiService->requestApi($personnage->getIdApi(),'biography');
        $heroAppearance = $apiService->requestApi($personnage->getIdApi(),'appearance');

        return $this->render('home/caracteristique.html.twig', [
            'user' => $user,
            'personnage' => $personnage,
            'personnages'=> $personnages,
            'powerstats' => $heroPower,
            'biography' => $heroBiography,
            'appearance' => $heroAppearance,
        ]);
    }

    /**
     * @Route("/show/{id}", name="show_personnage", methods={"GET"})
     */
    public function viewOtherPersonnage(PersonnageRepository $personnageRepository, Personnage $personnage)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_register');
        }
        $userPersonnage = $personnageRepository->findOneBy(['user'=>$user]);
        $otherUser = $personnage->getUser();
        $userItems = $otherUser->getItems();
        $personnages = $personnageRepository->findAll();

        return $this->render('home/personnage.html.twig', [
            'personnage' => $userPersonnage,
            'personnages' => $personnages,
            'user' => $user,
            'user_personnage' => $personnage,
            'user_items' => $userItems,
        ]);
    }

    /**
     * @Route("/locations", name="show_location")
     */
    public function showLocations(PersonnageRepository $personnageRepository)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_register');
        }
        $personnage = $personnageRepository->findOneBy(['user'=>$user]);
        $personnages = $personnageRepository->findAll();

        return $this->render('home/location.html.twig', [
            'personnage' => $personnage,
            'personnages' => $personnages,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/objets", name="show_objets")
     */
    public function showObjets(PersonnageRepository $personnageRepository, ItemRepository $itemRepository)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_register');
        }
        $personnage = $personnageRepository->findOneBy(['user'=>$user]);
        $personnages = $personnageRepository->findAll();
        $items = $itemRepository->findAll();
        $userItems = $user->getItems();

        return $this->render('home/objets.html.twig', [
            'personnage' => $personnage,
            'personnages' => $personnages,
            'user' => $user,
            'items' => $items,
            'user_items'=>$userItems,
        ]);
    }
}
