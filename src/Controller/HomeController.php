<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Trajet;
use App\Form\CommentaireType;
use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function redirectToHome(Request $request, EntityManagerInterface $manager)
    {
        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/home", name="home")
     */
    public function main(Request $request, EntityManagerInterface $manager, TrajetRepository $trajetRepository)
    {
        $user = $this->getUser();
        if ($user) {
            //dd($user);
            if ($user->getRoles()[0] === "ROLE_ADMIN") {
                return $this->redirectToRoute("admin");
            }
        }
        $form = $this->ajouter($request, $manager);
        $trajet_repositery = $this->getDoctrine()->getRepository(Trajet::class);
        $commentaire_repositery = $this->getDoctrine()->getRepository(Commentaire::class);
        $commentaires = $commentaire_repositery->findBy([], ['date' => 'DESC'], 3);
        $trajets = $trajetRepository->findByDate(new \DateTime("now"));
        // $trajets = $trajet_repositery->findBy([], ['Date' => 'ASC'], 21);
        $nb = (count($trajets) % 3) ? ((count($trajets) - count($trajets) % 3) / 3) + 1 : count($trajets) / 3;
        $city = file_get_contents(__Dir__ . '/../../public/Json/Cities.json');
        $cities = json_decode($city, true);
        return $this->render('home/main.html.twig', ['cities' => $cities, 'trajets' => $trajets, 'nb' => $nb, 'form' => $form->createView(), 'commentaires' => $commentaires]);
    }

    /**
     * @Route("/home/Avis", name="home_avis")
     */

    public function show(Request $request, EntityManagerInterface $manager)
    {
        $form = $this->ajouter($request, $manager);
        $commentaire_repositery = $this->getDoctrine()->getRepository(Commentaire::class);
        $commentaires = $commentaire_repositery->findBy([], ['date' => 'DESC']);
        return $this->render('home/Avis.html.twig', ['commentaire' => $commentaires, 'form' => $form->createView()]);
    }

    public function ajouter(Request $request, EntityManagerInterface $manager)
    {
        $info = $this->init_form();
        $form = $info['form'];
        $commentaire = $info['com'];
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setDate(new \DateTime());
            $manager->persist($commentaire);
            $manager->flush();

            $info = $this->init_form();
            $form = $info['form'];
        }
        return $form;
    }

    public function init_form()
    {
        $commentaire = new Commentaire();
        if (!(is_null($this->getUser()))) {
            $user = $this->getUser();
            $commentaire->setUser($user);
            $commentaire->setAuteur($user->getnom() . "_" . $user->getprenom());
        }
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->remove('date');
        return ['form' => $form, 'com' => $commentaire];
    }
}

