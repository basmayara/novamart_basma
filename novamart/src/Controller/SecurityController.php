<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(
        private RegistrationService $registrationService,
    ) {}

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error        = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $registrationForm = $this->createForm(RegistrationFormType::class, new User(), [
            'action' => $this->generateUrl('app_register'),
        ]);

        return $this->render('auth/login.html.twig', [
            'last_username'    => $lastUsername,
            'error'            => $error,
            'registrationForm' => $registrationForm->createView(),
        ]);
    }

    #[Route('/login-check', name: 'app_login_check')]
    public function loginCheck(): never
    {
        throw new \LogicException('This should never be reached.');
    }

    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $user             = new User();
        $registrationForm = $this->createForm(RegistrationFormType::class, $user, [
            'action' => $this->generateUrl('app_register'),
        ]);
        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $plainPassword = $registrationForm->get('plainPassword')->getData();
            $this->registrationService->register($user, $plainPassword);
            return $this->redirectToRoute('app_login');
        }

        return $this->render('auth/login.html.twig', [
            'last_username'    => $authenticationUtils->getLastUsername(),
            'error'            => $authenticationUtils->getLastAuthenticationError(),
            'registrationForm' => $registrationForm->createView(),
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void {}
}