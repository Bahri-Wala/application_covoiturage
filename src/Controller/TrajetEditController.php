<?php

namespace App\Controller;

use App\Entity\Trajet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrajetEditController extends AbstractController
{
    /**
     * @Route("/trajet/supprimer/{id}", name="trajet_supprimer")
     */
    public function index(Trajet $trajet = null, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $user->removeTrajet($trajet);

        $manager->remove($trajet);
        $manager->flush();
        return $this->redirectToRoute('profile');
    }
}


