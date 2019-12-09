<?php

namespace App\Controller;

// repository
use App\Repository\CategoryRepository;
use App\Repository\RegionRepository;

// symfony
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//form componments
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

// service
use App\Service\ArrayExport;

//entities
use App\Entity\Region;
use App\Entity\Category;
use App\Entity\RechercheEtt;





class ControllerBase extends AbstractController
{

    /**
     *   Index du site
     *
     * @Route("/")
     *
     **/
    public function index(CategoryRepository $catRep , RegionRepository $regRep , Request $request)
    {
        // on assaye de choper toutes les regions pour l'affichage


        $reg = $regRep->findAll();
        $cat = $catRep->findAll();

        $rechercheEntt = new RechercheEtt();

        $form =  $this->createFormBuilder($rechercheEntt)
            ->add('categorie', ChoiceType::class, ['choices' => [ArrayExport::exportCategoryToChoiceWithNull($cat)][0],'label' => false])
            ->add('region',ChoiceType::class, ['choices' => [ArrayExport::exportRegionToChoiceWithNull($reg)][0],'label' => false])
            ->add('searchString' ,TextType::class,['label' => false , 'required'=> false,])
            ->getForm();


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


            if($rechercheEntt->getSearchString() == null
            && $rechercheEntt->getRegion() == null
            && $rechercheEntt->getCategorie() == null) return $this->redirect('/annonces/offres');

            $strString = "?";
            if($rechercheEntt->getSearchString() != null) $strString .= "search=".$rechercheEntt->getSearchString();

            if($rechercheEntt->getRegion() != null){
                if($strString != "?")$strString .= "&region=".$rechercheEntt->getRegion();
                else $strString .= "region=".$rechercheEntt->getRegion();
            }

            if($rechercheEntt->getCategorie() != null){
                if($strString != "?")$strString .= "&category=".$rechercheEntt->getCategorie();
                else $strString .= "category=".$rechercheEntt->getCategorie();
            }


            return $this->redirect('/annonces/offres' . $strString);


        }

        var_dump('wesh');
        return $this->render('Base/index.html.twig',[ 'formRecherche' => $form->createView()]);
    }



    /**
     *
     * @Route("/404")
     *
     **/
    public function erroor()
    {
        return $this->render("404.html.twig",[]);

    }


}