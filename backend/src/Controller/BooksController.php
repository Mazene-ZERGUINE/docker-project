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
    private static $statusCodes = [
        "HTTP_OK" => 200 , 
        "HTTP_NOT_FOUND" => 404 ,
        "HTTP_BAD_REQUEST" => 400,
        "HTTP_SERVER_ERROR" => 500,
        "HTTP_CREATED" => 201 ,
    ] ;

    private static $headers = [
          "Content-Type" => "application/json"
    ] ;
    
    #[Route('/api/books', name: 'app_books_get_all' , methods:['GET'])]
    public function getAll(ManagerRegistry $doctrine): JsonResponse
    {   
        $books = $doctrine->getRepository(Books::class)->findAll();
        $result = array();
        foreach($books as $key => $value){
            $result[$key]  = [
                "isbn" => $value->getIsbn(),
               "title" => $value->getTitle(),
               "author" => $value->getAuthor(),
               "overview" =>  $value->getOverView() ,
               "picture" => $value->getpicture(),
               "read_count" => $value->getReadCount(),
               "created_at" => $value->getCreatedAt(),
                "updated_at" => $value->getupdatedAt()
            ] ;
        }
        return $this->json([
            "response_code" => $this::$statusCodes["HTTP_OK"] ,
            "headers" => $this::$headers ,
            "message" => "all books list" , 
            "data" => $result 
        ]);
    }

    #[Route('/api/book/{id}', name: 'app_books_get_one' , methods:['GET'])]
    public function getOneBook(ManagerRegistry $doctrine, $id): JsonResponse{

        if (is_numeric($id)) {
            $book = $doctrine->getRepository(Books::class)->findOneBy(["isbn" => $id]);
        } else {
            $book = $doctrine->getRepository(Books::class)->findOneBy(["title" => $id]) ;
        }
        if (!$book) {
            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_NOT_FOUND"] ,
                "headers" => $this::$headers ,
                "message" => "book not found" , 
            ]);
        }
        $result = [
            "isbn" => $book->getIsbn(),
            "title" => $book->getTitle(),
            "author" => $book->getAuthor(),
            "overview" =>  $book->getOverView() ,
            "read_count" => $book->getReadCount(),
            "picture" => $book->getpicture(),
            "created_at" => $book->getCreatedAt(),
            "updated_at" => $book->getupdatedAt()
        ] ; 
        return $this->json([
            "response_code" => $this::$statusCodes["HTTP_OK"] , 
            "headers" => $this::$headers, 
            "data" => $result 
        ]);
    }

    #[Route('/api/book', name: 'app_books_add' , methods:['POST'])]   
    public function addBook(ManagerRegistry $doctrine): JsonResponse{

        $json = json_decode(file_get_contents("php://input"));
        if (!property_exists($json, "title") || !property_exists($json, "author") || !property_exists($json, "isbn")) {
            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_BAD_REQUEST"] , 
                "headers" => $this::$headers,
                "message" => "author title and isbn properties are mandatory (please check)"
            ]);
        }

        if (strlen($json->isbn) > 13 || intval($json->isbn <= 0) ) {
            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_BAD_REQUEST"] , 
                "headers" => $this::$headers,
                "message" => "isbn value not allowed (isbn max 13 number and positif)" 
            ]);
        }

        $bookCheck = $doctrine->getRepository(Books::class)->findOneBy(["isbn" => $json->isbn]);
        if ($bookCheck) {
            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_BAD_REQUEST"] , 
                "headers" => $this::$headers, 
                "message" => "this isbn already existes in database"  
            ]);
        }

        $book = new Books() ;

        if (!property_exists($json, "overview")) {
            $book->setOverview("") ;
        }else {
            $book->setOverview($json->overview);
        }
        if (!property_exists($json, "read_count")) {
            $book->setReadCount(1) ;
        }else {
            $book->setReadCount($json->read_count);
        }
        $book->setIsbn($json->isbn) ;
        $book->setTitle($json->title) ;
        $book->setAuthor($json->author);
        $book->setCreatedAt(new DateTimeImmutable('now') , new DateTimeZone("Europe/Paris"));
        $book->setUpdatedAt(null);
        try {
            $entityManger = $doctrine->getManager() ;
            $entityManger->persist($book);
            $entityManger->flush();

            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_CREATED"],
                "headers" => $this::$headers,
                "message" => "book added to database"
            ])  ;
        } catch(Error $e) {
            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_SERVER_ERROR"],
                "headers" => $this::$headers ,
                "message" => $e ,
            ])  ;
        }
    }
    
    #[Route('/api/book/{id}/delete', name: 'app_books_delete' , methods:['DELETE'])]
    public function deleteBook(ManagerRegistry $doctrine , $id): JsonResponse {
        $book = $doctrine->getRepository(Books::class)->findOneBy(["isbn" => $id]);
        if (!$book) {
            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_NOT_FOUND"] ,
                "headers" => $this::$headers ,
                "message" => "book not found" , 
            ]);
        }
        try {
        $entityManger = $doctrine->getManager(); 
        $entityManger->remove($book) ;
        $entityManger->flush() ;
        return $this->json([
            "response_code" => $this::$statusCodes["HTTP_OK"],
            "headers" => $this::$headers,
            "message" => "book deleted" 
        ]) ;
        } catch(Error $e) {
            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_SERVER_ERROR"],
                "headers" => $this::$headers ,
                "message" => $e ,
            ])  ;
        }
    } 

    #[Route('/api/book/{id}/edit', name: 'app_books_edit' , methods:['PATCH'])]
    public function editBook(ManagerRegistry $doctrine , $id) : JsonResponse {

        $book = $doctrine->getRepository(Books::class)->findOneBy(["isbn" => $id]) ;

        if (!$book) {
            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_NOT_FOUND"] ,
                "headers" => $this::$headers ,
                "message" => "book not found" , 
            ]);
        }

        $json = json_decode(file_get_contents("php://input")) ;

        if (property_exists($json , "title")) {
            $book->setTitle($json->title) ;
        }
        if (property_exists($json , "author")) {
            $book->setAuthor($json->author) ;
        }
        if (property_exists($json , "isbn")) {
            if (strlen($json->isbn) > 13 || intval($json->isbn <= 0) ) {
                return $this->json([
                    "response_code" => $this::$statusCodes["HTTP_BAD_REQUEST"] , 
                    "headers" => $this::$headers,
                    "message" => "isbn value not allowed (isbn max 13 number and positif)" 
                ]);
            }
            $book->setIsbn($json->isbn) ;
        }
        if (property_exists($json , "overview")) {
            $book->setOverview($json->overview) ;
        }
        if (property_exists($json , "picture")) {
            $book->setPicture($json->picture) ;
        }

        if (property_exists($json , "read_count")) {
            if ($json->read_count < 0) {
                return $this->json([
                    "response_code" => $this::$statusCodes["HTTP_BAD_REQUEST"] , 
                    "headers" => $this::$headers,
                    "message" => "read_count can't be a negative value" 
                ]);
            } 
            $book->setReadCount($json->read_count) ;
        }

        $book->setUpdatedAt(new DateTimeImmutable('now' , new DateTimeZone("Europe/Paris")));

        $entityManger = $doctrine->getManager() ;
        try {
            $entityManger->flush();
            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_OK"],
                "headers" => $this::$headers,
                "message" => "book updated" 
            ]) ;
        } catch(Error $e) {
            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_SERVER_ERROR"],
                "headers" => $this::$headers ,
                "message" => $e ,
            ])  ;
        }
    }

}
