<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PurchaseController
 * @package App\Controller
 */
class PurchaseController extends AbstractController
{
    /**
     * @Route("/purchase/new", name="purchase_new")
     */
    public function new(): Response
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('isVerifiedCheck',$user);

        return $this->render('purchase/index.html.twig', [
            'controller_name' => 'PurchaseController',
        ]);
    }
}
