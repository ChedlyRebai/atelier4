<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/author/new' ,name: 'app_author_new')]
    public function new(): Response
    {
        return $this->render('author/new.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
        
    }

    #[Route('/author/edit/{id}', name: 'app_author_edit')]
    public function edit($id):Response{
        return $this->render('author/edit.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    
    #[Route('/author/delete/{id}', name: 'app_author_delete')]
    public function delete($id):Response{
        return $this->render('author/delete.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
}
