<?php

namespace App\Controller;

use App\Entity\Lending;
use App\Form\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

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
        $user = $this->security->getUser();

        $lengingRepository = $this->getDoctrine()->getRepository(Lending::class);
        $notifications = [
            'lendingNotifications' => $user ? $lengingRepository->findNotificationNumber($user->getId(), 'lender')[0]['nbNewMessages'] : null,
            'borrowingNotifications' => $user ? $lengingRepository->findNotificationNumber($user->getId(), 'borrower')[0]['nbNewMessages'] : null,
        ];
        return $notifications;
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
            'notifications' => $this->getNotificationsArray(),
        ]);
    }
}
