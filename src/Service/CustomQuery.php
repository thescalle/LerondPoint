<?php

namespace App\Service;


// Entities
use App\Entity\Region;
use App\Entity\Category;
use App\Entity\Offre;

// doctrine
use Doctrine\ORM\EntityManagerInterface;



class CustomQuery{



    public static function getOffres( EntityManagerInterface $em, $str = null, Category $cat=null , Region $reg=null)
    {
        $queryStr = "SELECT offre.id,offre.title,offre.description,offre.price,offre.address,offre.city,offre.image1,offre.image2,offre.image3,region.name as region,category.name as category,offre.date,user.name as name,user.first_name as firstName  FROM offre ";

        $queryStr.= "INNER JOIN `user` ON offre.user_id = user.id INNER JOIN `region` ON offre.region_id = region.id INNER JOIN `category` ON offre.category_id = category.id ";

        if($str != null){
            $str = '%'.$str.'%';
            $queryStr.= "WHERE offre.title LIKE :titleVar ";
        }

        if($cat != null){
            if($str != null){
                $queryStr.= "AND offre.category_id = :catId ";
            }
            else{
                $queryStr.= "WHERE offre.category_id = :catId ";
            }
        }

        if($reg != null){
            if($str != null || $cat != null){
                $queryStr.= "AND offre.region_id = :regId ";
            }
            else{
                $queryStr.= "WHERE offre.region_id = :regId ";
            }
        }

        $queryStr.= "ORDER BY offre.date ASC";
        $connection = $em->getConnection();
        $statement = $connection->prepare($queryStr);

        if($str != null)$statement->bindValue('titleVar', $str);
        if($cat != null)$statement->bindValue('catId', $cat->getId());
        if($reg != null)$statement->bindValue('regId', $reg->getId());

        $statement->execute();
        return $results = $statement->fetchAll();
    }


    public static function getDemandes( EntityManagerInterface $em, $str = null, Category $cat=null , Region $reg=null)
    {
        $queryStr = "SELECT demande.id,demande.title,demande.description,demande.date,region.name as region,category.name as category,user.name as name,user.first_name as firstName FROM demande ";

        $queryStr.= "INNER JOIN `user` ON demande.user_id = user.id INNER JOIN `region` ON demande.region_id = region.id INNER JOIN `category` ON demande.category_id = category.id ";


        if($str != null || $str !=""){
            $str = '%'.$str.'%';
            $queryStr.= "WHERE demande.title LIKE :titleVar ";
        }

        if($cat != null){
            if($str != null){
                $queryStr.= "AND demande.category_id = :catId ";
            }
            else{
                $queryStr.= "WHERE demande.category_id = :catId ";
            }
        }

        if($reg != null){
            if($str != null || $cat != null){
                $queryStr.= "AND demande.region_id = :regId ";
            }
            else{
                $queryStr.= "WHERE demande.region_id = :regId ";
            }
        }
        $queryStr.= "ORDER BY demande.date ASC";

        $connection = $em->getConnection();
        $statement = $connection->prepare($queryStr);

        if($str != null)$statement->bindValue('titleVar', $str);
        if($cat != null)$statement->bindValue('catId', $cat->getId());
        if($reg != null)$statement->bindValue('regId', $reg->getId());

        $statement->execute();
        return $results = $statement->fetchAll();
    }



}