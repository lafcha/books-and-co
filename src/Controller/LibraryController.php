<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\UsersBook;
use App\Form\BookSearchType;
use App\Form\BookType;
use App\Form\UsersBookType;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Repository\UsersBookRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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
        $limit = 10;
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
        $usersBookTotal = (int)$usersBookRepository->getUsersBookById($userId);
        
        // find all books of a user with a $limit of element by page
        $usersBooks = $usersBookRepository->findAllByUserId($userId, $page, $limit);

        if (empty($usersBooks) && $usersBookTotal != 0) {
            // throw 404 if the page returns an empty array
            throw $this->createNotFoundException('Cette page n\'existe pas');
        }
        return $this->render('library/browse.html.twig', [
            'usersBooks' => $usersBooks,
            'currentPage' => $page,
            'usersBookTotal' => $usersBookTotal,
            'usersBookLimit' => $limit,
        ]);
    }

    /**
     * @Route("/ajout", name="book_add")
     */
    public function book_add($userSlug, UserRepository $userRepository, BookRepository $bookRepository, UsersBookRepository $usersBookRepository, UserInterface $user, Request $request): Response
    {
        $error = '';
        $usersBook = new UsersBook;
        $usersBook->setUser($user);

        //get the user by slug
        $libraryUser = $userRepository->findOneBy(['slug' => $userSlug]);
        
        $searchForm = $this->createForm(BookSearchType::class);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            //get the book id founded by the isbn
            $book = $bookRepository->findOneBy(['isbn' => $searchForm->getData()['isbn']]);
            if ($book !== null) {
                $usersBook->setBook($book);

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
                $book->setSlug($slugger->slugify($book->getTitle() . '-' . $book->getIsbn()));
                $em = $this->getDoctrine()->getManager();
                $em->persist($book);
                $em->flush();
                $usersBook->setBook($book);
                $em->persist($usersBook);
                $em->flush();

                return $this->redirectToRoute('library_browse', [
                    'userSlug'=> $userSlug,
                ]);
            } else {
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
            'action' => $this->generateUrl('library_browse', [
                'userSlug' => $userSlug,
            ]),
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
            // throw 404 if the book doesn't exist
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
    public function book_edit($userSlug, $slug, BookRepository $bookRepository, Request $request): Response
    {
        //get the book by slug
        $book = $bookRepository->findOneBy(['slug' => $slug]);

        // create the form
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugger = new Slugify();
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
    public function book_delete($userSlug, $slug, BookRepository $bookRepository, UsersBookRepository $usersBookRepository, UserInterface $user): Response
    {
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
