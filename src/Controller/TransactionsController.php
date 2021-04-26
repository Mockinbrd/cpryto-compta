<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TransactionsController
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class TransactionsController extends AbstractController
{
    /**
     * @Route("/api/transactions/{email}", name="transactions.user", methods={"GET"})
     */
    public function getUserTransactions(User $user): Response
    {
        if ($user !== $this->getUser()){
            return $this->json([
                "error" => "Only owners can see their transactions."
            ], 400);
        }

        return $this->json([
            'transactions' => $user->getAllTransactions()
        ], 200);
    }
}
