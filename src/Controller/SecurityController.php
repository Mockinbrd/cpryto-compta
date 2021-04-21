<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login", methods={"GET","POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('home');
         }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/api/auth", name="authenticate", methods={"POST"})
     */
    public function authenticate(AuthenticationUtils $authenticationUtils): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                'error' => 'Invalid login request: check that the Content-Type header is "application/json".'
            ], 400);
        }

        return $this->json([
            'user' => $this->getUser()->getId() ??  null
        ], 200);
    }

    /**
     * @Route("/api/get_token_payloads", name="get_token_payloads", methods={"GET"})
     */
    public function getTokenPayloads(Request $request, JWTEncoderInterface $jwt, DecoderInterface $decoder): Response
    {
        if ($request->query->get('authorization_token') !== null){
            $token = $request->query->get('authorization_token');
            try {
                return $this->json([
                    'user' => $jwt->decode($token)
                ]);
            } catch (JWTDecodeFailureException $e) {
                throw new Exception([
                    'error' => [
                        'code' => 400,
                        'message' => $e->getMessage()
                    ]
                ]);
            }
        }

        return $this->json([
            'error' => 'No token provided, please provide a JWT token in a "authorization_token" key.'
        ], 400);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
