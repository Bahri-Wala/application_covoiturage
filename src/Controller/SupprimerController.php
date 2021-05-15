<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Trajet;
use App\Entity\User;
use App\Repository\CommentaireRepository;
use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SupprimerController extends AbstractController
{

    /**
     * @Route("/supprimer/user/{id}", name="supprimer_user")
     */
    public function remove_user(User $user = null, CommentaireRepository $commentaireRepository, TrajetRepository $trajetRepository, EntityManagerInterface $manager)
    {
        $trajets = $trajetRepository->findBy(['user' => $user]);


        $commentaires = $commentaireRepository->findBy(['user' => $user]);
        foreach ($trajets as $cle => $value) {
            $user->removeTrajet($value);
            $manager->remove($value);
            $manager->flush();
        }
        foreach ($commentaires as $cle => $value) {
            $user->removeCommentaire($value);
            $manager->remove($value);
            $manager->flush();
        }
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('admin');
    }


    /**
     * @Route("/supprimer/trajet/{id}", name="supprimer_trajet")
     */
    public function remove_trajet(Trajet $trajet = null, EntityManagerInterface $manager)
    {
        $user = $trajet->getUser();
        $user->removeTrajet($trajet);

        $manager->remove($trajet);
        $manager->flush();
        return $this->redirectToRoute('trajets');
    }

    /**
     * @Route("/supprimer/commentaire/{id}", name="supprimer_commentaire")
     */
    public function remove_commentaire(Commentaire $commentaire = null, EntityManagerInterface $manager)
    {
        $user = $commentaire->getUser();
        if ($user) {
            $user->removeCommentaire($commentaire);
        }
        $todo = $manager->getRepository(Commentaire::class, $commentaire);
        $manager->remove($commentaire);
        $manager->flush();
        return $this->redirectToRoute('commentaire');


    }
}
