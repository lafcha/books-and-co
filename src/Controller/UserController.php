<?php

namespace App\Controller;

use App\Form\EditProfilType;
use App\Entity\User;
use Gedmo\Sluggable\Util\Urlizer;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
     * @Route("/mon-compte", name="account_")
     */
class UserController extends AbstractController
{
    /**
     * @Route("/edit", name="edit"), methods={"GET", "POST"})
     */
    public function edit(Request $request, UserInterface $user, EntityManagerInterface $em, UploaderHelper $uploaderHelper): Response
    {
        $form = $this->createForm(EditProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilData = $form->getData();
             /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile){
                $newFilename = $uploaderHelper->uploadAvatar($uploadedFile);
                
                $profilData->setAvatar($newFilename);
            }

            $em->persist($user);
            $em->flush();
            $this->addFlash('message', 'Profil mis à jour');
        }

        return $this->render('user/profil-edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/mot-de-passe", name="edit_password"), methods={"GET", "POST"})
     */
    public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            if($request->request->get('pass') == $request->request->get('pass2')){
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                $em->flush();
                $this->addFlash('message', 'Mot de passe mis à jour avec succès');

                return $this->redirectToRoute('account_edit');
            }else{
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }

        return $this->render('user/profil-edit-password.html.twig');
    }
}
