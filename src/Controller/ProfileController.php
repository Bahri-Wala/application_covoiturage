<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\AddVoitureType;
use App\Form\EditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getuser();
        if (is_null($user->getvoiture())) {
            $voiture = new Voiture();
        } else {
            $voiture = $user->getVoiture();
        }
        $form = $this->createForm(AddVoitureType::class, $voiture);
        $form->remove("user");
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $voiture->setUser($user);
            $manager->persist($voiture);
            $manager->flush();
            return $this->redirectToRoute('profile');
        }
        return $this->render('profile/index.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/profile/edit", name="profile_edit")
     */
    public function editprofile(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getuser();
        $form = $this->createForm(EditType::class, $user);
        $form->remove("image");
        $form->remove("email");
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!(($form['Image']->getData()) === "empty")) {
                $systemfile = new Filesystem();
                $file = $user->getimage();
                if (!(($file === 'layout/images/femme.jpg') || ($file === 'layout/images/homme.jpg'))) {
                    $systemfile->remove(__DIR__ . '/../../public/' . $file);
                }
                $image = $form['Image']->getData();
                $imagepath = md5(uniqid()) . $image->getClientOriginalName();
                $destination = __DIR__ . '/../../public/assets/uploads';
                try {
                    $image->move($destination, $imagepath);
                    $user->setimage('assets/uploads/' . $imagepath);
                } catch (FileException $fe) {
                    echo $fe;
                }
            } else {
                $user->setimage($user->getimage());
            }
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("profile");
            //return $this->forward('App\Controller\SecurityController::login');
        }
        return $this->render("profile/edit.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/profile/resetpassword", name="profile_reset_password")
     */
    public function reset_password(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getuser();
        $form = $request->request;
        if ($encoder->isPasswordValid($user, $form->get('oldpass'))) {
            if ($form->get('newpass') === $form->get('confpass')) {
                if (strlen($form->get('newpass')) >= 8) {
                    $hash = $encoder->encodePassword($user, $form->get('newpass'));
                    $user->setPassword($hash);
                    $manager->persist($user);
                    $manager->flush();
                    $this->addFlash('success', 'le Mot de passe a été changé');
                    return $this->redirectToRoute('profile');
                } else {
                    $this->addFlash('error', 'la longueur min du mot de passe doit étre 8');
                    return $this->render('profile/reset_password.html.twig');
                }

            } else {
                $this->addFlash('error', 'Les mots de passe doit étre identiques');
                return $this->render('profile/reset_password.html.twig');
            }
        } else {
            $this->addFlash('error', "le mot de passe courant n'est pas correcte");
            return $this->render('profile/reset_password.html.twig');
        }
    }

}
