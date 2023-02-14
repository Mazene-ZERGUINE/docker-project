<?php

namespace App\Controller;

use App\Entity\Books;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BooksRepository;

class BooksController extends AbstractController
{

    #[Route('/api/books', name: 'app_books_get_all' , methods:['GET'])]
    public function getAll(ManagerRegistry $doctrine): JsonResponse
    {   
        $books = $doctrine->getRepository(Books::class)->findAll() ; 
        $result = array(); 
        foreach($books as $key => $value){
            $result[$key]  = [
               "title" => $value->getTitle(),
               "author" => $value->getAuthor(),
               "overview" =>  $value->getOverView() ,
               "picture" => $value->getpicture(),
               "created_at" => $value->getCreatedAt(),
                "updated_at" => $value->getupdatedAt()
            ] ;
        }
        return $this->json([
            "response_code" => 200 ,
            "message" => "" , 
            "data" => $result 
        ]);
    }
}
