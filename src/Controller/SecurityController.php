<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)//: Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/oubli_pass",name="app_forgotten_password")
     */
    public function forgotpass(Request $request, UserRepository $userrep, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator)
    {
        $form = $this->createForm(ResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donnees = $form->getData();
            $user = $userrep->findOneByEmail($donnees['email']);
            if (!$user) {
                $this->addFlash('danger', "Cet utilisateur n'existe pas");
                return $this->render('app_login');
            }


            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $entitymanager = $this->getDoctrine()->getManager();
                $entitymanager->persist($user);
                $entitymanager->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger', "une erreur est survenue..! ");
                return $this->redirectToRoute('app_login');
            }

            $url = $this->generateUrl('app_reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);


            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom('petsi760@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    "<h3>Bonjour</h3>,<br><br><p>Une demande de réinitialisation de mot de passe a été effectuée pour le Site Wassalni.<br>
                       Veuillez cliquez  sur le lien suivant : " . $url . "</p>", 'text/html'
                );

            $mailer->send($message);

            $this->addFlash('message', "un e-mail de réinitialisation de mot de passe a été envoyé");

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/Forgot_pass.html.twig', ['emailForm' => $form->createView()]);
    }

    /**
     * @Route("/reset_pass/{token}",name="app_reset_pass")
     */
    public function resetPass($token, Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);

        if (!$user) {
            $this->addFlash('danger', 'Token inconnu');
            return $this->redirectToRoute('app_login');
        }
        if ($request->isMethod('POST')) {
            $form = $request->request;
            if ($form->get('pass') === $form->get('confpass')) {
                if (strlen($form->get('pass')) >= 8) {
                    $user->setResetToken(null);
                    $hash = $encoder->encodePassword($user, $form->get('pass'));
                    $user->setPassword($hash);
                    $manager->persist($user);
                    $manager->flush();
                    $this->addFlash('success', 'le Mot de passe a été changé');
                    return $this->redirectToRoute('app_login');
                } else {
                    $this->addFlash('error', 'la longueur min du mot de passe doit étre 8');
                    return $this->render("security/reset_pass.html.twig", ['token' => $token]);
                }

            } else {
                $this->addFlash('error', 'Les mots de passe doit étre identiques');
                return $this->render("security/reset_pass.html.twig", ['token' => $token]);
            }
        } else {
            return $this->render("security/reset_pass.html.twig", ['token' => $token]);
        }

    }
}
