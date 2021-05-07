<?php

namespace App\Controller;

use App\Repository\LendingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/mes-prets", name="lending")
 */
class LendingController extends AbstractController
{
    /**
     * @Route("", name="_browse")
     */
    public function browse(LendingRepository $lendingRepository, UserInterface $user): Response
    {
        $lendingDatas = $lendingRepository->findAllByLenderId($user->getId());
        
        return $this->render('lending/browse.html.twig', [
            'lendingDatas' => $lendingDatas,
            ]
        );
    }
}
