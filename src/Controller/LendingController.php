<?php

namespace App\Controller;

use App\Entity\Lending;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\LendingRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/mes-prets", name="lending_")
 */
class LendingController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse(Request $request, LendingRepository $lendingRepository, UserInterface $user): Response
    {
        // set the limit of elements by page
        $elementsLimit = 10;
        // get the page in url
        $page = (int)$request->query->get("page", 1);
        if ($page < 1) {
            $page = 1;
        }

        //get status in url to filter lendings
        $statusFilter = $request->query->get("status", null);
        switch ($statusFilter) {
            case 'attente':
                $statusFilter = 0;
                break;
            case 'prete':
                $statusFilter = 1;
                break;
            case 'archive':
                $statusFilter = 2;
                break;
            default:
                $statusFilter = null;
                break;
        }
        
        $userId = $user->getId();

        $elementsTotal = (int)$lendingRepository->getLendingCountByLenderId($userId, $statusFilter);
        //lending list
        $lendingDatas = $lendingRepository->findAllByLenderId($userId, $page, $elementsLimit, $statusFilter);

        if (empty($lendingDatas) && $elementsTotal != 0) {
            // throw 404 if the page returns an empty array
            throw $this->createNotFoundException('Cette page n\'existe pas');
        }

        return $this->render('lending/browse.html.twig', [
            'lendingDatas' => $lendingDatas,
            'currentPage' => $page,
            'elementsTotal' => $elementsTotal,
            'elementsLimit' => $elementsLimit,
            ]
        );
    }

    /**
     * @Route("/{id}", name="read")
     */
    public function read(Request $request, Lending $lending, LendingRepository $lendingRepository, MessageRepository $messageRepository, UserInterface $user): Response
    {
        $lending = $lendingRepository->findAllLendingStats($lending->getId());

        //TODO replace this with voters
        if ($user->getId() != $lending->getUsersBook()->getUser()->getId()) {
            throw $this->createNotFoundException('Ce prêt n\'est pas disponible');
        }
        // when the user arrives on the page, the unread messages becomes readed
        $unreadMessages = $messageRepository->findAllUnreadMessagesByLendingIdAndUserId($lending->getId(), $user->getId());
        if ($unreadMessages != null) {
            foreach ($unreadMessages as $unreadMessage) {
                $unreadMessage->setIsRead(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($unreadMessage);
            }
            //flush all updated unread messages to readed
            $em->flush();
        }

        $message = new Message();
        $sendMessageForm = $this->createForm(MessageType::class, $message);
        $sendMessageForm->handleRequest($request);

        if ($sendMessageForm->isSubmitted() && $sendMessageForm->isValid()) {
            // set the sender as the user interface and lending as current lending
            $message->setSender($user);
            $message->setLending($lending);

            //flush the new messages
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            //redirect to the current route to come back in post and directly have the message
            return $this->redirectToRoute('lending_read', [
                'id' => $lending->getId(),
            ]);
        }

        return $this->render('lending/read.html.twig', [
            'lendingData' => $lending,
            'sendMessageForm' => $sendMessageForm->createView(),
            ]
        );
    }
    /**
     * @Route("/{id}/{action}", name="action")
     */
    public function action(Lending $lending, $action): Response
    {
        switch ($action) {
            case 'waiting':
                $newStatus = 0;
                break;
            case 'lended':
                $newStatus = 1;
                break;
            case 'archive':
                $newStatus = 2;
                break;
            // throw 404 if this actions isn't above
            default:
                throw $this->createNotFoundException('action non répertoriée');
        }
        
        //update the lending status in DB
        $lending->setStatus($newStatus);
        $em = $this->getDoctrine()->getManager();
        $em->persist($lending);
        $em->flush();

        //return to the updated lending page
        return $this->redirectToRoute('lending_read', [
            'id' => $lending->getId(),
        ]);
    }
}
