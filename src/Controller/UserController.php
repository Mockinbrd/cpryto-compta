<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/api/me", name="api.user.me", methods={"GET"})
     */
    public function me(SerializerInterface $serializer): Response
    {
        return $this->json([
            $serializer->serialize($this->getUser(), 'json', [
                'groups' => ['me:read']
            ])
        ]);

    }
}
