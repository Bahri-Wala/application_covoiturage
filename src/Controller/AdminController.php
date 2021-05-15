<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Trajet;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();
        return $this->render('admin/index.html.twig', ['users' => $users
        ]);
    }

    /**
     * @Route("/admin/commentaire",name="commentaire")
     */
    public function comment()
    {
        $repository = $this->getDoctrine()->getRepository(Commentaire::class);
        $commentaires = $repository->findAll();
        return $this->render('admin/commentaire.html.twig', ['commentaires' => $commentaires
        ]);
    }

    /**
     * @Route("/admin/trajets",name="trajets")
     */
    public function gestionTrajets()
    {
        $repository = $this->getDoctrine()->getRepository(Trajet::class);
        $trajets = $repository->findAll();
        return $this->render('admin/trajets.html.twig', ['trajets' => $trajets
        ]);
    }
}
