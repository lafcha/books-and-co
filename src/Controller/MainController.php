<?php

namespace App\Controller;

use App\Form\SearchFormType;
use App\Repository\UsersBookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    protected function navSearchForm(){
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
        $searchForm = $this->createForm(SearchFormType::class, null, [
            'action' => $this->generateUrl('search'),
            'method' => 'GET',
        ]);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'searchForm' => $searchForm->createView(),
        ]);
    }
}
