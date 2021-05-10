<?php

namespace App\Controller;

use App\Entity\Lending;
use App\Entity\Message;
use App\Form\BorrowingFormType;
use App\Form\MessageType;
use App\Repository\LendingRepository;
use App\Repository\MessageRepository;
use App\Repository\UsersBookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("", name="borrowing_")
 */
class BorrowingController extends AbstractController
{
    /**
     * @Route("/demande-de-pret", name="form")
     */
    public function form(Request $request, UserInterface $user, UsersBookRepository $usersbook): Response
    {
        $lending = new Lending();
        $message = new Message();

        // Retriving the users book id from post through users_book
        if(isset($_POST['users_book'])){
        
            //Retrieving the users book id
            $usersBookRequest = $request->request->all('users_book');
  
            $usersBookId = $usersBookRequest['id'];
            
        // Retriving the users book id from post through borrowing_from
        } elseif (isset($_POST['borrowing_form'])){

            $usersBookId =  $_POST['borrowing_form']['id'];

        } else {
            throw $this->createNotFoundException('Vous n\'avez pas sélectionné de livre'); 
        }
            

            $form = $this->createForm(BorrowingFormType::class, null, [
                'usersBookId'=> $usersBookId
            ]);
            
            $form->handleRequest($request);
    
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            //Filling the new lending entity with the info collected
            
            $usersBookEntity = $usersbook->findOneBy(['id'=> $usersBookId]);

            $lending->setBorrower($user);
            $lending->setUsersBook($usersBookEntity);

            $em = $this->getDoctrine()->getManager();
            $em->persist($lending);
            $em->flush();

            // Filling the new message entity with the info from the newly lending entity created, the form & the user
            
            $messageContent = $form->getData()['message'];

            $message->setSender($user);
            $message->setLending($lending);
            $message->setContent($messageContent);
           
            $em->persist($message);
            $em->flush();
    
            return $this->redirectToRoute('borrowing_read', [
                'id' => $lending->getId(),
            ]);
        
        }

        return $this->render('borrowing/form.html.twig', [
            'borrowingForm' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/mes-emprunts", name="browse")
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

        $userId = $user->getId();

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

        $elementsTotal = (int)$lendingRepository->getLendingCountByBorrowerId($userId, $statusFilter);
        //lending list
        $lendingDatas = $lendingRepository->findAllByBorrowerId($userId, $page, $elementsLimit, $statusFilter);

        return $this->render('borrowing/browse.html.twig', [
            'lendingDatas' => $lendingDatas,
            'currentPage' => $page,
            'elementsTotal' => $elementsTotal,
            'elementsLimit' => $elementsLimit,
            ]
        );
    }

    /**
     * @Route("/mes-emprunts/{id}", name="read")
     */
    public function read(Request $request, Lending $lending, LendingRepository $lendingRepository, MessageRepository $messageRepository, UserInterface $user): Response
    {
        $lending = $lendingRepository->findAllLendingStats($lending->getId());

        //TODO replace this with voters
        if ($user->getId() != $lending->getBorrower()->getId()) {
            throw $this->createNotFoundException('Ce prêt n\'est pas disponible');
        }
        // when the user arrives on the page, the unread messages becomes read
        $unreadMessages = $messageRepository->findAllUnreadMessagesByLendingIdAndUserId($lending->getId(), $user->getId());
        if ($unreadMessages != null) {
            foreach ($unreadMessages as $unreadMessage) {
                $unreadMessage->setIsRead(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($unreadMessage);
            }
            $em->flush();
        }
        $message = new Message();
        $sendMessageForm = $this->createForm(MessageType::class, $message);

        $sendMessageForm->handleRequest($request);

        if ($sendMessageForm->isSubmitted() && $sendMessageForm->isValid()) {
            // set the sender as the user interface and lending as current lending
            $message->setSender($user);
            $message->setLending($lending);

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('borrowing_read', [
                'id' => $lending->getId(),
            ]);
        }


        return $this->render('borrowing/read.html.twig', [
            'lendingData' => $lending,
            'sendMessageForm' => $sendMessageForm->createView(),
            ]
        );
    }
}
