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
        $books = $doctrine->getRepository(Books::class)->findAll();
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

    #[Route('/api/books/{id}', name: 'app_books_get_one' , methods:['GET'])]
    public function getOneBook(ManagerRegistry $doctrine, $id): JsonResponse{

        if (gettype($id) === "integer") {
            $book = $doctrine->getRepository(Books::class)->find($id);

            if (!$book) {
                return $this->json([
                    "response_code" => 400 , 
                    "message" => "book not found (try with an other id)"
                ]);
            }
            $result = [
                "title" => $book->getTitle(),
                "author" => $book->getAuthor(),
                "overview" =>  $book->getOverView() ,
                "picture" => $book->getpicture(),
                "created_at" => $book->getCreatedAt(),
                "updated_at" => $book->getupdatedAt()
            ] ; 
            return $this->json([
                "response_code" => 200 ,
                "message" => "" , 
                "data" => $result 
            ]);
        } else {
            $book = $doctrine->getRepository(Books::class)->findByName($id);

            if (!$book) {
                return $this->json([
                    "response_code" => 400 , 
                    "message" => "book not found (try with an other id)"
                ]);
            }
            $result = [
                "title" => $book[0]->getTitle(),
                "author" => $book[0]->getAuthor(),
                "overview" =>  $book[0]->getOverView() ,
                "picture" => $book[0]->getpicture(),
                "created_at" => $book[0]->getCreatedAt(),
                "updated_at" => $book[0]->getupdatedAt()
            ] ; 

            return $this->json($result) ;
        } 

    }

}
