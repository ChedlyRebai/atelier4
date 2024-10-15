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

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/',name:'book_index',methods:['GET'])]
    public function index(BookRepository $bookrepo): Response
    {
        return $this->render('book/index.html.twig',[
            'books'=>$bookrepo->findAll(),
        ]);
    }

    #[Route('/new',name:'book_new',methods:['GET','POST'])]
    public function new(AuthorRepository $authorepo, Request $request,EntityManagerInterface $entitymanager):Response {
        if($request->isMethod('POST')){
            $book = new Book();
            
            $book->setTitle($request->request->get('title'));
            $publishedDate = $request->request->get('published_date');
            if ($publishedDate) {
                $book->setPublicationDate(new \DateTime($publishedDate));
            } else {
                throw new \Exception('Published date is required');
            }

            //$book->setPublicationDate($request->request->get('published_date'));
            $book->setEnabled($request->request->get('enabled'));
            $author = $entitymanager->getRepository(Author::class)->find($request->request->get('author'));
            $book->setAuthor($author);

            
            $entitymanager->persist($book);
            $entitymanager->flush();
            
            return $this->redirectToRoute('book_index');
        }
        return $this->render('book/new.html.twig',[
            'authors'=>$authorepo->findAll(),
        ]);
    }

    #[Route('/{id}/edit',name:'book_edit',methods:['GET','POST'])]
    public function edit(Request $request,Book $book,EntityManagerInterface $entitymanager):Response {
        if($request-> isMethod('POST')){
            $book->setTitle($request->request->get("title"));
            $entitymanager->flush();
            return $this->redirectToRoute('book_index');
        }
        return $this->render('book/edit.html.twig',[
            'book'=>$book,
        ]);
    }

    #[Route('/{id}',name:'book_delete',methods:['POST'])]
    public function delete(Request $request,Book $book,EntityManagerInterface $entitymanager):Response {
        if($this->isCsrfTokenValid('delete'.$book->getId(),$request->request->get('_token'))){
            $entitymanager->remove($book);
            $entitymanager->flush();
        }
        return $this->redirectToRoute('book_index');
    }



    #[Route('/{id}',name:'book_show',methods:['GET'])]
    public function show(Book $book):Response {
        return $this->render('book/show.html.twig',[
            'book'=>$book,
        ]);
    }




}