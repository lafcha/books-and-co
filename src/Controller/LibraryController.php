<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Book;
use App\Form\BookType;
use App\Entity\UsersBook;
use Cocur\Slugify\Slugify;
use App\Form\UsersBookType;
use App\Form\BookSearchType;
use App\Service\UploaderHelper;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Repository\UsersBookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/bibliotheque/{userSlug}", name="library_")
 */
class LibraryController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse($userSlug, UsersBookRepository $usersBookRepository, UserRepository $userRepository, Request $request): Response
    {
        // set the limit of elements by page
        $elementsLimit = 10;
        // get the page in url
        $page = (int)$request->query->get("page", 1);
        if ($page < 1) {
            $page = 1;
        }
        $user = $userRepository->findOneBy(['slug' => $userSlug]);
        if (!$user) {
            // throw 404 if the book doesn't exist
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }
        $userId = $user->getId();
        // get the count of usersBook
        $elementsTotal = (int)$usersBookRepository->getUsersBookCountById($userId);
        
        // find all books of a user with a $limit of element by page
        $usersBooks = $usersBookRepository->findAllByUserId($userId, $page, $elementsLimit);

        if (empty($usersBooks) && $elementsTotal != 0) {
            // throw 404 if the page returns an empty array
            throw $this->createNotFoundException('Cette page n\'existe pas');
        }

        return $this->render('library/browse.html.twig', [
            'usersBooks' => $usersBooks,
            'currentPage' => $page,
            'elementsTotal' => $elementsTotal,
            'elementsLimit' => $elementsLimit,
        ]);
    }

    /**
     * @Route("/ajout", name="book_add")
     */
    public function book_add($userSlug, UserRepository $userRepository, BookRepository $bookRepository, UserInterface $user, Request $request, UploaderHelper $uploaderHelper): Response
    {
        //get the user by slug
        $libraryUser = $userRepository->findOneBy(['slug' => $userSlug]);
        if (!$libraryUser) {
            // throw 404 if the book doesn't exist
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }


        //error will be displayed in twig if there is many error
        $error = '';

        $usersBook = new UsersBook;
        $usersBook->setUser($user);

        //get the user by slug
        $libraryUser = $userRepository->findOneBy(['slug' => $userSlug]);

        //set the bookSearchTypeOtion because we can't have empty array of options
        $bookSearchTypeOtion = '';
        //set the $bookIsbn with an isbn if the isbn is in post
        $bookIsbn = empty($request->request->all('book_search')['isbn']) ? false : $request->request->all('book_search')['isbn'];
        // if $bookIsbn exists
        if ($bookIsbn) {
            //if the isbn validation is correct
            if (preg_match('/^\d{13}$/i' ,$bookIsbn)) {
                
                $bookSearchTypeOtion = $bookRepository->findOneBy(['isbn' => $bookIsbn]);
                //if the book at given isbn exists
                if ($bookSearchTypeOtion) {
                    //set the informative sentence about the book
                    $bookSearchTypeOtion = 'Je recherche bien le livre ' . $bookSearchTypeOtion->getTitle() . ' par ' . $bookSearchTypeOtion->getAuthor();
                } else {
                    //the book doesn't exist
                    $error = 'Ce livre n\'existe pas, ajoutez le !';
                }
            } else {
                //the isbn isn't correct
                $bookSearchTypeOtion = 'isbn invalide';
                $error = 'isbn invalide';
            }
        }
        //creating the form with informative sentence about the book
        $searchForm = $this->createForm(BookSearchType::class, null, [
            'label' => $bookSearchTypeOtion,
        ]);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid() && $searchForm->getData()['book']) {

            //get the book id founded by the isbn
            $book = $bookRepository->findOneBy(['isbn' => $searchForm->getData()['isbn']]);
            //if the books exists
            if ($book !== null) {
                $usersBook->setBook($book);

                //Add the book in DB
                $em = $this->getDoctrine()->getManager();
                $em->persist($usersBook);
                $em->flush();

                return $this->redirectToRoute('library_browse', [
                    'userSlug'=> $userSlug,
                ]);
            }
            $error = 'Ce livre n\'existe pas, ajoutez le !';
        }
        $book = new Book();

        $bookForm = $this->createForm(BookType::class, $book);
        $bookForm->handleRequest($request);

        if ($bookForm->isSubmitted()) {
            if ($bookForm->isValid() === true) {
                $slugger = new Slugify();
                // set slug with title and isbn
                $book->setSlug($slugger->slugify($book->getTitle() . '-' . $book->getIsbn()));
                 //add the book cover if there's one
                 /** @var UploadedFile $uploadedFile */
                 $uploadedFile = $bookForm['coverFile']->getData();
                 if ($uploadedFile){
                     $newFilename = $uploaderHelper->uploadAvatar($uploadedFile);
             
                     $book->setCover($newFilename);
                 }
                //Add the book in DB
                $em = $this->getDoctrine()->getManager();
                $em->persist($book);
                $em->flush();
                //Directly add the added book to the user's library
                $usersBook->setBook($book);
                $em->persist($usersBook);
                $em->flush();

                return $this->redirectToRoute('library_browse', [
                    'userSlug'=> $userSlug,
                ]);
            } else {
                // if the form is submitted and not valid, we add an error 
                $error = 'Le formulaire est invalide';
            }
        }
        return $this->render('library/book/add.html.twig', [
            'searchForm' => $searchForm->createView(),
            'bookForm' => $bookForm->createView(),
            'libraryUser' => $libraryUser,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/{slug}", name="book_read")
     */
    public function book_read($userSlug, $slug, UserRepository $userRepository, BookRepository $bookRepository, UsersBookRepository $usersBookRepository): Response
    {
        
        //get the user by slug
        $libraryUser = $userRepository->findOneBy(['slug' => $userSlug]);
        //get the book by slug
        $book = $bookRepository->findOneBy(['slug' => $slug]);
        
        $usersBook = $usersBookRepository->findOneBy(['user' => $libraryUser, 'book' => $book]);
        
        //create a form for UsersBook in action to method 'form' in the BorrowingController
        $form = $this->createForm(UsersBookType::class, $usersBook, [
            'action' => $this->generateUrl('borrowing_form'),
            'method' => 'POST',
        ]);

        if (!$libraryUser) {
            // throw 404 if the book doesn't exist
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }
        if (!$book) {
            // throw 404 if the book doesn't exist
            throw $this->createNotFoundException('Ce livre n\'existe pas');
        }
        if (!$usersBook) {
            // throw 404 if the combination of user and book doesn't exist
            throw $this->createNotFoundException('Cet utilisateur ne possède pas ce livre');
        }
        return $this->render('library/book/read.html.twig', [
            'book' => $book,
            'libraryUser' => $libraryUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/modifier", name="book_edit")
     */
    public function book_edit($userSlug, $slug, UserRepository $userRepository, BookRepository $bookRepository, Request $request, UploaderHelper $uploaderHelper): Response
    {
        //get the user by slug
        $libraryUser = $userRepository->findOneBy(['slug' => $userSlug]);
        if (!$libraryUser) {
            // throw 404 if the book doesn't exist
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }
        //get the book by slug
        $book = $bookRepository->findOneBy(['slug' => $slug]);

        // create the form
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugger = new Slugify();
            //edit the book cover if there's one
                 /** @var UploadedFile $uploadedFile */
                 $uploadedFile = $form['coverFile']->getData();
                 if ($uploadedFile){
                     $newFilename = $uploaderHelper->uploadAvatar($uploadedFile);
             
                     $book->setCover($newFilename);
                 }
            // set slug with title and isbn
            $book->setSlug($slugger->slugify($book->getTitle() . '-' . $book->getIsbn()));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('library_book_read', [
                'userSlug'=> $userSlug,
                'slug' => $book->getSlug()
            ]);
        }

        return $this->render('library/book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/supprimer", name="book_delete")
     */
    public function book_delete($userSlug, $slug, UserRepository $userRepository, BookRepository $bookRepository, UsersBookRepository $usersBookRepository, UserInterface $user): Response
    {
        //get the user by slug
        $libraryUser = $userRepository->findOneBy(['slug' => $userSlug]);
        if (!$libraryUser) {
            // throw 404 if the book doesn't exist
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }
        //get the book by slug
        $book = $bookRepository->findOneBy(['slug' => $slug]);

        //get the user book to delete thanks to user id and book id
        $userBook = $usersBookRepository->findOneBy([
            'user' => $user->getId(),
            'book' => $book->getId(),
        ]);

        $em = $this->getDoctrine()->getManager();
        $em->remove($userBook);
        $em->flush();

        return $this->redirectToRoute('library_browse', [
            'userSlug'=> $userSlug,
        ]);
    }
}
