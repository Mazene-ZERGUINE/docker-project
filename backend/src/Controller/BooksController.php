<?php

namespace App\Controller;

use App\Entity\Books;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BooksRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Error;

class BooksController extends AbstractController
{

    #[Route('/api/books', name: 'app_books_get_all' , methods:['GET'])]
    public function getAll(ManagerRegistry $doctrine): JsonResponse
    {   
        $books = $doctrine->getRepository(Books::class)->findAll();
        $result = array();
        foreach($books as $key => $value){
            $result[$key]  = [
                "id" => $value->getId(),
                "isbin" => $value->getIsbin(),
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
                "id" => $book->getId(),
                "isbin" => $book->getIsbin(),
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
                "id" => $book->getId(),
                "isbin" => $book->getIsbin(),
                "title" => $book[0]->getTitle(),
                "author" => $book[0]->getAuthor(),
                "overview" =>  $book[0]->getOverView() ,
                "picture" => $book[0]->getpicture(),
                "created_at" => $book[0]->getCreatedAt(),
                "updated_at" => $book[0]->getupdatedAt()
            ];

            return $this->json($result) ;
        } 
    }

    #[Route('/api/book', name: 'app_books_add' , methods:['POST'])]   //adapter pour lise dans fichier json?
    public function addBook(ManagerRegistry $doctrine): JsonResponse{

        $json = json_decode(file_get_contents("php://input"));
        if (!property_exists($json, "title") || !property_exists($json, "author") || !property_exists($json, "isbin")) {
            return $this->json([
                "response_code" => 400 , 
                "message" => "author title and isbin properties are mandatory (please check)"
            ]);
        }
        $book = new Books() ;

        if (!property_exists($json, "overview")) {
            $book->setOverview("") ;
        }else {
            $book->setOverview($json->overview);
        }
        $book->setIsbin($json->isbin) ;
        $book->setTitle($json->title) ;
        $book->setAuthor($json->author);
        $book->setReadCount(1) ;
        $book->setCreatedAt(new DateTimeImmutable('now'));
        $book->setUpdatedAt(null);
        
        $entityManger = $doctrine->getManager() ;
        $entityManger->persist($book);
        $entityManger->flush();

        return $this->json([
            "response_code" => 200 ,
            "message" => "book added to database"
        ])  ;
    }   

}
