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

class SearchController extends MainController
{
    /**
     * @Route("/recherche", name="search")
     */
    public function search(UsersBookRepository $usersBookRepository, Request $request): Response
    {   
        // For now, we want to show every book available in the department
        // We're not searching with keywords !
        $form = $this->createForm(SearchFormType::class, null, [
            'method' => 'GET',
        ]);

        //get the county in URL
        $countyValue = (isset($request->query->all()['search_form']['county'])) ? $request->query->all()['search_form']['county'] : false;
        $book = $usersBookRepository->findAllAvalaibleBooksByCity($countyValue);

        return $this->render('search/search.html.twig', [
            'form' => $form->createView(),
            'navSearchForm' => $form->createView(),
            'book' => $book,
            'countyValue' => $countyValue,
            'navSearchForm' => $this->navSearchForm()->createView(),
            'notifications' => $this->getNotificationsArray(),
        ]);
    }

}
