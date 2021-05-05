<?php

namespace App\Controller;

use App\Entity\Lending;
use App\Entity\Message;
use App\Entity\User;
use App\Form\BorrowingFormType;
use App\Repository\UsersBookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/demande-de-pret/", name="borrowing_")
 */
class BorrowingController extends AbstractController
{
    /**
     * @Route("", name="form")
     */
    public function form(Request $request, UserInterface $user, UsersBookRepository $usersbook): Response
    {
        $lending = new Lending();
        $message = new Message();

        $usersBookRequest = $request->request->all('users_book');
  
        $usersBookRequestId = $usersBookRequest['id']; 
  
        dd($usersBookRequest);
        $form = $this->createForm(BorrowingFormType::class, null, [
            'usersBookId'=> $usersBookRequestId
        ]);
       
        $form->handleRequest($request);

  
        if ($form->isSubmitted() && $form->isValid()) {

            //Filling the new lending entity with the info collected
               //Retrieving the UsersBook entity with the current usersbook_id
        
    
            //$usersBookEntity = $usersbook->findOneBy(['id'=> $usersBookId]);

            $lending->setBorrower($user);
            $lending->setUsersBook($usersBookEntity);

            $em = $this->getDoctrine()->getManager();
            $em->persist($lending);
            $em->flush();

            // Filling the new message entity with the info from the newly lending entity created, the form & the user
            
            // Retrieving the message content from the form
            $messageContent = $form['message']->getData();

            $message->setSender($user);
            $message->setLending($lending->getId());
            $message->setContent($messageContent);
           
            $em->persist($message);
            $em->flush();
    
        return $this->redirectToRoute('accueil_browse');
                
        }

        return $this->render('borrowing/form.html.twig', [
            'borrowingForm' => $form->createView(),
        ]);

    } 

}
    


