<?php

namespace App\Service;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
//entity
use App\Entity\User;

class MessagerieQuery
{


    public static function getAllMessageries(User $user, EntityManagerInterface $em)
    {

        $queryStr = "SELECT messagerie.id,usr1.id as id1,usr1.name as name1,usr1.first_name as firstName1,usr2.id as id2,usr2.name as name2,usr2.first_name as firstName2 FROM `messagerie` INNER JOIN user as usr1 ON messagerie.user_one_id = usr1.id INNER JOIN user as usr2 ON messagerie.user_two_id = usr2.id WHERE messagerie.user_one_id = :id OR messagerie.user_two_id = :id";

        $connection = $em->getConnection();
        $statement = $connection->prepare($queryStr);


        $statement->bindValue('id', $user->getId());

        $statement->execute();
        $final = $results = $statement->fetchAll();
        $finalObj = [];
        $i = 0;
        foreach ($final as $value){

            if($value['id1'] == $user->getId())
            {
                $finalObj[$i]['id'] = $value['id'];
                $finalObj[$i]['userId'] = $value['id2'];
                $finalObj[$i]['userName'] = $value['name2'];
                $finalObj[$i]['userFirstName']=$value['firstName2'];
            }
            elseif ($value['id2'] == $user->getId())
            {
                $finalObj[$i]['id'] = $value['id'];
                $finalObj[$i]['userId'] = $value['id1'];
                $finalObj[$i]['userName'] = $value['name1'];
                $finalObj[$i]['userFirstName']=$value['firstName1'];
            }
            $i++;
        }

        return $finalObj;

    }












}



