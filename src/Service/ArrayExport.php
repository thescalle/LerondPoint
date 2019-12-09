<?php


namespace App\Service;

use App\Entity\Category;
use App\Entity\Region;



class ArrayExport
{

    public static function exportCategoryToChoice($Cats)
    {
        $final = [];
        foreach ($Cats as $categorie) {
            $final[ $categorie->getName()] = $categorie->getName();
        }
        return $final;
    }




    public static function exportRegionToChoice($Regs)
    {
        $final = [];
        foreach ($Regs as $region) {
            $final[ $region->getName()] = $region->getName();
        }
        return $final;
    }



    public static function exportCategoryToChoiceWithNull($Cats)
    {
        $final = [];
        $final["Toutes les categories"] = null;
        foreach ($Cats as $categorie) {
            $final[ $categorie->getName()] = $categorie->getName();
        }
        return $final;
    }

    public static function exportRegionToChoiceWithNull($Regs)
    {
        $final = [];
        $final["Toutes la france"] = null;
        foreach ($Regs as $region) {
            $final[ $region->getName()] = $region->getName();
        }
        return $final;
    }


}