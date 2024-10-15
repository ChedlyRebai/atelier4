<?php

namespace App\Controller;


namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
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
    public function new(Request $request,EntityManagerInterface $entitymanager):Response {
        if($request->isMethod('POST')){
            $book = new Book();
            $book->setTitle($request->request->get('title'));
            $book->setPublicationDate($request->request->get('published_date'));
            $book->setEnabled($request->request->get('enabled'));
            $book->setAuthor($request->request->get('author'));
            $entitymanager->persist($book);
            $entitymanager->flush();
            
            return $this->redirectToRoute('book_index');
        }
        return $this->render('book/new.html.twig');
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