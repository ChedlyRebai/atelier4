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
        return $this->render('book/index.twig.html',[
            'books'=>$bookrepo->findAll(),
        ]);
    }

   /* #[Route('/new',name:'book_new',methods:['GET','POST'])]
    public function new(Request $request,EntityManagerInterface $entitymanager):Response {
        if($request-> isMethod('POST')){
            $book=new Book();
            $book->setTitle($request->request->get("title"));
        }
    }*/


}