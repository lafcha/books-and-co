<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\UsersBookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ma-bibliotheque", name="library_")
 */
class LibraryController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse(UsersBookRepository $usersBookRepository): Response
    {
        //$libraryArray = $usersBookRepository->findBy(['userId' => 279]);
        $libraryArray = $usersBookRepository->findAllByUserId(279);
        return $this->render('library/browse.html.twig', [
            'libraryArray' => $libraryArray,
        ]);
    }
}
