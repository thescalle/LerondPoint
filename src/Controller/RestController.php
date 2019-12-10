<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Service\CustomQuery;

class RestController extends AbstractController
{
    /**
     *
     * @Route("/api/v1/offre")
     *
     **/
    public function rest_offres(EntityManagerInterface $em)
    {
        $reponse = new Response();
        $qr = new CustomQuery();

        $strSearch = null;    $category = new Category();      $region = new Region();
        $category = null;    $region = null;
        if(isset( $_GET['search']))$strSearch =  $_GET['search'];
        if(isset($_GET['category'])) $category = $em->getRepository(Category::class)->findOneBy(['name'=> $_GET['category']]);
        if(isset($_GET['region'])) $region = $em->getRepository(Region::class)->findOneBy(['name'=>$_GET['region']]);



        if(isset($_GET['id'])) {
            $connection = $em->getConnection();
            $statement = $connection->prepare("SELECT * FROM offre as off WHERE off.id = ".$_GET['id']);
            $statement->execute();
            $results = $statement->fetchAll();
            $reponse->setContent( json_encode( $results));
            $reponse->headers->set('Content-Type', 'application/json');
            return $reponse;
        }

        $result = $qr->getOffres($em,$strSearch,$category,$region);

        $reponse->setContent( json_encode( $result));
        $reponse->headers->set('Content-Type', 'application/json');
        return $reponse;
    }


    /**
     *
     * @Route("/api/v1/demande")
     *
     **/
    public function rest_demandes(EntityManagerInterface $em)
    {
        $reponse = new Response();
        $qr = new CustomQuery();

        $strSearch = null;    $category = new Category();      $region = new Region();
        $category = null;
        $region = null;

        if(isset( $_GET['search']))$strSearch =  $_GET['search'];
        if(isset($_GET['category'])) $category = $em->getRepository(Category::class)->findOneBy(['name'=> $_GET['category']]);
        if(isset($_GET['region'])) $region = $em->getRepository(Region::class)->findOneBy(['name'=>$_GET['region']]);

        if(isset($_GET['id'])) {
            $connection = $em->getConnection();
            $statement = $connection->prepare("SELECT * FROM demande as off WHERE off.id = ".$_GET['id']);
            $statement->execute();
            $results = $statement->fetchAll();
            $reponse->setContent( json_encode( $results));
            $reponse->headers->set('Content-Type', 'application/json');
            return $reponse;
        }

        $result = $qr->getDemandes($em,$strSearch,$category,$region);

        $reponse->setContent( json_encode( $result));
        $reponse->headers->set('Content-Type', 'application/json');
        return $reponse;
    }

}
