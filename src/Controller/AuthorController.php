<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\AuthorType;

use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/author')]
class AuthorController extends AbstractController
{
    #[Route('/', name: 'author_index', methods: ['GET'])]
    public function index(AuthorRepository $authorRepository): Response
    {
        return $this->render('author/index.html.twig', [
            'authors' => $authorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'author_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        /*if ($request->isMethod('POST')) {
            $author = new Author();
            $author->setUsername($request->request->get('username'));
            $author->setEmail($request->request->get('email'));
    
            $entityManager->persist($author);
            $entityManager->flush();
    
            return $this->redirectToRoute('author_index');
        }*/
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($author);
            $entityManager->flush();

         return $this->redirectToRoute('author_index');
         }
    
        return $this->render('author/new.html.twig',[
            'form'=>$form
        ]);
    }
    
    #[Route('/{id}/edit', name: 'author_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Author $author, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(AuthorType::class, $author);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('author_index');
    }

    return $this->render('author/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}
    
    #[Route('/{id}', name: 'author_delete', methods: ['POST'])]
    public function delete(Request $request, Author $author, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$author->getId(), $request->request->get('_token'))) {
            $entityManager->remove($author);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('author_index');
    }
}
?>