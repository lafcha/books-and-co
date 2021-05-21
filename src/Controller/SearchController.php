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
use Symfony\Component\Security\Core\User\UserInterface;

class SearchController extends MainController
{
    /**
     * @Route("/recherche", name="search")
     */
    public function search(UsersBookRepository $usersBookRepository, ?UserInterface $user , Request $request): Response
    {   
        // For now, we want to show every book available in the department
        // We're not searching with keywords !
        $form = $this->createForm(SearchFormType::class, null, [
            'method' => 'GET',
            ]);
            
        // set the limit of elements by page
        $elementsLimit = 10;
        // get the page in url
        $page = (int)$request->query->get("page", 1);
        if ($page < 1) {
            $page = 1;
        }
        //get the county in URL
        $countyValue = (isset($request->query->all()['search_form']['county'])) ? $request->query->all()['search_form']['county'] : false;

        if ($user) {
            $books = $usersBookRepository->findAllAvalaibleBooksByCity($countyValue, $page, $elementsLimit, $user->getId());
            
            $elementsTotal = (int)$usersBookRepository->findAllAvalaibleBooksByCityCount($countyValue, $user->getId());
        } else {
            $books = $usersBookRepository->findAllAvalaibleBooksByCity($countyValue, $page, $elementsLimit);
            
            $elementsTotal = (int)$usersBookRepository->findAllAvalaibleBooksByCityCount($countyValue);
        }

        return $this->render('search/search.html.twig', [
            'currentPage' => $page,
            'elementsTotal' => $elementsTotal,
            'elementsLimit' => $elementsLimit,
            'form' => $form->createView(),
            'navSearchForm' => $form->createView(),
            'book' => $books,
            'countyValue' => $countyValue,
            'navSearchForm' => $this->navSearchForm()->createView(),
            'notifications' => $this->getNotificationsArray(),
        ]);
    }

}
