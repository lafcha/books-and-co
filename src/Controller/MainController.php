<?php

namespace App\Controller;

use App\Form\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * return nav bar form to search books
     *
     * @return form
     */
    protected function navSearchForm(){
        $searchForm = $this->createForm(SearchFormType::class, null, [
            'action' => $this->generateUrl('search'),
            'method' => 'GET',
        ]);
        return $searchForm;
    }
    /**
     * return an array of 2 entries ( lend notification and borrow notification)
     *
     * @return Array
     */
    protected function getNotificationsArray(){
        $searchForm = $this->createForm(SearchFormType::class, null, [
            'action' => $this->generateUrl('search'),
            'method' => 'GET',
        ]);
        return $searchForm;
    }

    /**
     * @Route("/", name="accueil_browse")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'searchForm' => $this->navSearchForm()->createView(),
            'navSearchForm' => $this->navSearchForm()->createView(),
        ]);
    }
}
