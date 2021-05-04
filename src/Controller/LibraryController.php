<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\UsersBookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/ma-bibliotheque", name="library_")
 */
class LibraryController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse(UserInterface $user, UsersBookRepository $usersBookRepository, Request $request): Response
    {
        // set the limit of elements by page
        $limit = 10;
        // get the page in url
        $page = (int)$request->query->get("page", 1);

        $userId = $user->getId();
        // find all books of a user with a $limit of element by page
        // get the count of usersBook
        $usersBookTotal = (int)$usersBookRepository->getUsersBookById($userId);
        
        $usersBooks = $usersBookRepository->findAllByUserId($userId, $page, $limit);
        if (empty($usersBooks)) {
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
    public function book_read($slug, BookRepository $bookRepository): Response
    {
        //get the book by slug
        $book = $bookRepository->findOneBy(['slug' => $slug]);
        if (!$slug) {
            // throw 404 if the book doesn't exist
            throw $this->createNotFoundException('Ce livre n\'existe pas');
        }
        return $this->render('library/book/read.html.twig', [
            'book' => $book
        ]);
    }
}
