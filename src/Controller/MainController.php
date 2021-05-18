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
    protected function navSearchForm()
    {
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
    protected function getNotificationsArray()
    {
        $user = $this->security->getUser();

        $lengingRepository = $this->getDoctrine()->getRepository(Lending::class);
        $lenderMsg = $lengingRepository->findNotificationNumber($user->getId(), 'lender');
        $borrowerMsg = $lengingRepository->findNotificationNumber($user->getId(), 'borrower');
        $notifications = [
            'lendingNotifications' => $user ? (isset($lenderMsg[0]['nbNewMessages']) ? $lenderMsg[0]['nbNewMessages'] : null) : null,
            'borrowingNotifications' => $user ? (isset($borrowerMsg[0]['nbNewMessages']) ? $borrowerMsg[0]['nbNewMessages'] : null) : null,
        ];
        return $notifications;
    }

    /**
     * @Route("/", name="accueil_browse")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'searchForm' => $this->navSearchForm()->createView(),
            'navSearchForm' => $this->navSearchForm()->createView(),
            'notifications' => $this->getNotificationsArray(),
        ]);
    }

    /**
     * @Route("/apropos", name="apropos")
     */
    public function apropos(): Response
    {
        return $this->render('main/apropos.html.twig', [
            'navSearchForm' => $this->navSearchForm()->createView(),
            'notifications' => $this->getNotificationsArray(),

        ]);
    }
}