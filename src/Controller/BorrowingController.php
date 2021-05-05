<?php

namespace App\Controller;

use App\Entity\Lending;
use App\Entity\Message;
use App\Entity\User;
use App\Form\BorrowingFormType;
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
    public function form(Request $request, UserInterface $user): Response
    {
        $lending = new Lending();
        $message = new Message;

  
        $form = $this->createForm(BorrowingFormType::class, $message);
        $form->handleRequest($request);

        dd($request->request->all(['users_book']));

        if ($form->isSubmitted() && $form->isValid()) {

            // Récupérer le users book id
            
        

            // Récupérer le message du formulaire
            $messageContent = $form['message']->getData();

            // On modifie la nouvelle occurence de lending :

            // Mettre l'id de l'utilisateur en tant que borrower id
            $lending->setBorrower($user);

            // Mettre le users book id
            $lending->setUsersBook($usersBookId);

            // on enregistre lending :
            $em = $this->getDoctrine()->getManager();
            $em->persist($lending);
            $em->flush();

            // On modifie la nouvelle occurence de message :
       
            // Mettre l'id de l'utilsateur en tant que sender Id
            //$message->setSender($userId);

            // Mettre le lending id en tant que lending id
            //$message->setLending($lending->getId());

            // Mettre le message du formulaire dans content
            //$message->setContent($messageContent);

            //$em = $this->getDoctrine()->getManager();
            //$em->persist($message);
            $em->flush();
    
            return $this->redirectToRoute('acceuil_browse');
                
        }

        return $this->render('borrowing/form.html.twig', [
            'borrowingForm' => $form->createView(),
        ]);

    } 

}
    


