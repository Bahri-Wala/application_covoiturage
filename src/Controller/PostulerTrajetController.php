<?php


namespace App\Controller;


use App\Entity\Trajet;
use App\Form\AddTrajetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostulerTrajetController extends AbstractController
{
    /**
     * @Route("/home/Postuler_trajet",name="postuler")
     */
    public function postuler(Request $request,EntityManagerInterface $manager){

        $user = $this->getUser();
        $trajet = new Trajet();
            $form = $this->createForm(AddTrajetType::class, $trajet, ['method' => 'POST']);
            $form->remove("user");
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $trajet->setUser($user);
                $manager->persist($trajet);
                $manager->flush();
                return $this->redirectToRoute("profile");
                }
            return $this->render('trajet_edit/postuler_trajet.html.twig', ['form' => $form->createView()]);
    }
}