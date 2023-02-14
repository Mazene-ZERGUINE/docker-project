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
    // http return status codes 
    private static $statusCodes = [
        "HTTP_OK" => 200 , 
        "HTTP_NOT_FOUND" => 404 ,
        "HTTP_BAD_REQUEST" => 400,
        "HTTP_SERVER_ERROR" => 500
    ] ;
    
    // applications headers 
    private static $headeres = [
          "Content-Type" => "application/json"
    ] ;

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
            "response_code" => $this::$statusCodes["HTTP_OK"] ,
            "headers" => $this::$headeres ,
            "message" => "all books list" , 
            "data" => $result 
        ]);
    }

    #[Route('/api/book/{id}', name: 'app_books_get_one' , methods:['GET'])]
    public function getOneBook(ManagerRegistry $doctrine, $id): JsonResponse{

        if (is_numeric($id)) {
            $book = $doctrine->getRepository(Books::class)->findOneBy(["isbin" => $id]);
        } else {
            $book = $doctrine->getRepository(Books::class)->findOneBy(["title" => $id]) ;
        }
        if (!$book) {
            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_NOT_FOUND"] ,
                "headers" => $this::$headeres ,
                "message" => "book not found" , 
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
            "response_code" => $this::$statusCodes["HTTP_OK"] , 
            "headers" => $this::$headeres, 
            "data" => $result 
        ]);
    }

    #[Route('/api/book', name: 'app_books_add' , methods:['POST'])]   //adapter pour lise dans fichier json?
    public function addBook(ManagerRegistry $doctrine): JsonResponse{

        // checking the not null properties 
        $json = json_decode(file_get_contents("php://input"));
        if (!property_exists($json, "title") || !property_exists($json, "author") || !property_exists($json, "isbin")) {
            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_BAD_REQUEST"] , 
                "headers" => $this::$headeres,
                "message" => "author title and isbin properties are mandatory (please check)"
            ]);
        }

        $bookCheck = $doctrine->getRepository(Books::class)->findOneBy(["isbin" => $json->isbin]);
        if ($bookCheck) {
            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_BAD_REQUEST"] , 
                "headers" => $this::$headeres, 
                "message" => "this isbin already existes in database"  
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
        try {
            $entityManger = $doctrine->getManager() ;
            $entityManger->persist($book);
            $entityManger->flush();

            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_OK"],
                "headers" => $this::$headeres,
                "message" => "book added to database"
            ])  ;
        } catch(Error $e) {
            return $this->json([
                "response_code" => $this::$statusCodes["HTTP_SERVER_ERROR"],
                "headers" => $this::$headeres ,
                "message" => $e ,
            ])  ;
        }
    }   

}
