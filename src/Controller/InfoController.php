<?php

namespace App\Controller;

use App\Entity\Trajet;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends AbstractController
{
    /**
     * @Route("/info_user/{id}", name="info_user")
     */
    public function show_profile(User $user)
    {
        return $this->render("info/profile_info.html.twig", ['user' => $user]);
    }

    /**
     * @Route("/info_trajet/{id}", name="info_trajet")
     */
    public function show_trajet(Trajet $trajet)
    {
        return $this->render("info/trajet_info.html.twig", ['trajet' => $trajet]);
    }
}
