<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->remove("image");
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setRoles(['ROLE_USER']);
            if (!(($form['Image']->getData()) === 'empty')) {
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
                if ($user->getSexe() === 'Homme')
                    $user->setimage('layout/images/homme.jpg');
                else
                    $user->setimage('layout/images/femme.jpg');
            }

            $user->setActivationToken(md5(uniqid()));

            $manager->persist($user);
            $manager->flush();

            $message = (new \Swift_Message('Activation de votre compte'))
                ->setFrom('petsi760@gmail.com')
                ->setTo($user->getEmail())
                ->setBody($this->renderView('emails/activation.html.twig', ['token' => $user->getActivationToken()]), 'text/html');

            $mailer->send($message);


            /* $token = new UsernamePasswordToken(
                 $user,
                 $user->getPassword(),
                 'main',
                 $user->getRoles()
             );

             $this->get('security.token_storage')->setToken($token);
             $this->get('session')->set('_security_main', serialize($token));*/

            return $this->render("emails/notification.html.twig");
        }
        return $this->render("registration/registration.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/activation/{token}", name="activation")
     */
    public function activation($token, UserRepository $userrepo)
    {
        $user = $userrepo->findoneBy(['Activation_Token' => $token]);
        if (!$user) {
            throw $this->createNotFoundException("cet utilisateur n'existe pas ! ");
        }
        $user->setActivationToken(null);
        $entitymanager = $this->getDoctrine()->getManager();
        $entitymanager->persist($user);
        $entitymanager->flush();

        $token = new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            'main',
            $user->getRoles()
        );

        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', serialize($token));

        $this->addFlash('sucess', 'Compte bien activé ! ');

        return $this->redirectToRoute('home');
    }
}
