<?php

namespace App\Controller;

use App\Form\SearchFormType;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Repository\UsersBookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/recherche", name="search")
     */
    public function search(UsersBookRepository $usersBookRepository, Request $request): Response
    {   
        // For now, we want to show every book available in the department
        // We're not searching with keywords !
        $book = [];
        $form = $this->createForm(SearchFormType::class, null, [
            'method' => 'GET',
        ]);
        if($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $criteria = $form->getExtraData()['county'];
            $book = $usersBookRepository->findAllAvalaibleBooksByCity($criteria);
        }

        return $this->render('search/search.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

}
