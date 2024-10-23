<?php

namespace App\Controller;


namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

#[Route('/book')]
class BookController extends AbstractController
{

    // Exerice: order by date
    #[Route('/ordered', name: 'book_ordered', methods: ['GET'])]
    public function ordered(BookRepository $bookrepo): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookrepo->findAllOrderedByPublicationDate(),
        ]);
    }


    #[Route('/filter', name: 'book_filter', methods: ['GET', 'POST'])]
public function filter(Request $request, BookRepository $bookrepo): Response
{
    $form = $this->createFormBuilder()
        ->add('startDate', DateType::class, [
            'widget' => 'single_text',
            'required' => true,
        ])
        ->add('endDate', DateType::class, [
            'widget' => 'single_text',
            'required' => true,
        ])
        ->add('filter', SubmitType::class, ['label' => 'Filter'])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        // Save the filtered results in the session or flash message
        $this->addFlash('success', 'Filter applied!');

        // Redirect to a route with the start and end date as query parameters
        return $this->redirectToRoute('book_filter_results', [
            'startDate' => $startDate->format('Y-m-d'), // Format the date as needed
            'endDate' => $endDate->format('Y-m-d'),
        ]);
    }

    return $this->render('book/filter.html.twig', [
        'form' => $form->createView(),
    ]);
}


#[Route('/filter/results', name: 'book_filter_results', methods: ['GET'])]
public function filterResults(Request $request, BookRepository $bookrepo): Response
{
    // Get the query parameters
    $startDate = $request->query->get('startDate');
    $endDate = $request->query->get('endDate');

    // Convert query parameters to DateTime objects
    $startDateTime = new \DateTime($startDate);
    $endDateTime = new \DateTime($endDate);

    // Fetch filtered books
    $books = $bookrepo->findByDateRange($startDateTime, $endDateTime);

    return $this->render('book/filter_results.html.twig', [
        'startDate' => $startDateTime,
        'endDate' => $endDateTime,
        'books' => $books,
    ]);
}



    #[Route('/', name: 'book_index', methods: ['GET'])]
    public function index(BookRepository $bookrepo): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookrepo->findAll(),
        ]);
    }

    #[Route('/new', name: 'book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $book->getAuthor();
            $author->setNbBooks($author->getNbBooks() + 1);

            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $book->getAuthor();
            $author->setNbBooks($author->getNbBooks() + 1);

            $entityManager->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->request->get('_token'))) {
            $author = $book->getAuthor();
            $author->setNbBooks($author->getNbBooks() - 1);

            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index');
    }

    #[Route('/{id}', name: 'book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }




}