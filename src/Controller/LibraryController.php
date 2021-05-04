<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
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
        $user = $userRepository->findOneBy(['slug' => $userSlug]);
        // set the limit of elements by page
        $limit = 10;
        // get the page in url
        $page = (int)$request->query->get("page", 1);

        $userId = $user->getId();
        // find all books of a user with a $limit of element by page
        // get the count of usersBook
        $usersBookTotal = (int)$usersBookRepository->getUsersBookById($userId);
        
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
     * @Route("/{slug}", name="book_read")
     */
    public function book_read($userSlug, $slug, UserRepository $userRepository, BookRepository $bookRepository): Response
    {
        //get the user by slug
        $libraryUser = $userRepository->findOneBy(['slug' => $userSlug]);
        //get the book by slug
        $book = $bookRepository->findOneBy(['slug' => $slug]);
        if (!$slug) {
            // throw 404 if the book doesn't exist
            throw $this->createNotFoundException('Ce livre n\'existe pas');
        }
        return $this->render('library/book/read.html.twig', [
            'book' => $book,
            'libraryUser' => $libraryUser,
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
            $book->setSlug($slugger->slugify($book->getTitle()));
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le film ' . $book->getTitle() . ' a bien été <strong>modifié</strong>');

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
    public function book_delete($userSlug, $slug, UserRepository $userRepository, BookRepository $bookRepository, UsersBookRepository $usersBookRepository): Response
    {
        //get the book by slug
        $book = $bookRepository->findOneBy(['slug' => $slug]);
        //get the user by slug
        $user = $userRepository->findOneBy(['slug' => $userSlug]);
        //get the user book to delete thanks to user id and book id
        $userBook = $usersBookRepository->findOneBy([
            'user' => $user->getId(),
            'book' => $book->getId(),
        ]);

        $em = $this->getDoctrine()->getManager();
        $em->remove($userBook);
        $em->flush();
        
        $this->addFlash('success', 'Le film ' . $book->getTitle() . ' a bien été supprimé');

        return $this->redirectToRoute('library_browse', [
            'userSlug'=> $userSlug,
        ]);
    }
}
