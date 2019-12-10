<?php


namespace App\Controller;

// repository
use App\Repository\CategoryRepository;
use App\Repository\FavoriRepository;
use App\Repository\OffreRepository;
use App\Repository\RegionRepository;
use App\Repository\DemandeRepository;
use App\Repository\UserRepository;

// form extensions
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// services
use App\Service\ArrayExport;
use App\Service\CustomQuery;

// entities
use App\Entity\OffreEntt;
use App\Entity\Region;
use App\Entity\Offre;
use App\Entity\Demande;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\DemandeEntt;
use App\Entity\RechercheEtt;
use App\Entity\Favori;



class AnnonceController extends AbstractController
{







    /**
     *   On affiche toutes les offres
     *
     * @Route("/annonces/offres")
     *
     **/
    public function offres(CategoryRepository $catRep , RegionRepository $regRep , OffreRepository $annRep, Request $request, EntityManagerInterface $em)
    {


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


        $strRegion = ""; $strCategory = ""; $strSearch = "";
        if(isset($_GET['region']))$strRegion = $_GET['region'];
        if(isset($_GET['category'])) $strCategory = $_GET['category'];
        if(isset($_GET['search']))$strSearch = $_GET['search'];


        $categorySearchMe = new Category();      $regionSearchMe = new Region();
        $categorySearchMe = null;    $regionSearchMe = null;  $strSearchMe = null;
        if(isset( $_GET['search'])) $strSearch =  $_GET['search'];
        if(isset($_GET['category'])) $categorySearchMe = $em->getRepository(Category::class)->findOneBy(['name'=> $_GET['category']]);
        if(isset($_GET['region'])) $regionSearchMe = $em->getRepository(Region::class)->findOneBy(['name'=>$_GET['region']]);


        $annonces = CustomQuery::getOffres($em, $strSearch , $categorySearchMe , $regionSearchMe);


        $params = [ 'SearchString' => $strSearch , 'Region' => $strRegion , 'Categorie' => $strCategory , 'annonceSize' => sizeof($annonces) ];

        return $this->render("Annonces/offres.html.twig",[
            'Params'=> $params,
            'Annonces' => $annonces,
            'Regions'=>$reg,
            'Categories' => $cat,
            'formRecherche' => $form->createView()
        ]);
    }



    /**
     *
     * @Route("/annonces/offres/{id}")
     *
     **/
    public function offre($id, OffreRepository $annRep, FavoriRepository $fvRep, UserRepository $userRep, EntityManagerInterface $em){
        $annonce = $annRep->findOneBy(['id' => $id]);

        if(isset($_POST['addFav'])){
            $fav = new Favori();
            $fav->setUser($userRep->findOneBy(['id'=>$this->get('session')->get('id')]));
            $fav->setOffre($annonce);
            $em->persist($fav);
            $em->flush();
        }
        else if(isset($_POST['rmFav'])){
            $user =$userRep->findOneBy(['id'=>$this->get('session')->get('id')]);
            $fav = $fvRep->findOneBy(['user'=>$user, 'offre'=>$annonce]);
            if(isset($fav)) {
                $em->remove($fav);
                $em->flush();
            }
        }

        if($annonce == null) return $this->render("404.html.twig",[]);
        $params = [];
        if($this->get('session')->has('id')) if($annonce->getUser()->getId() == $this->get('session')->get('id')) $params['isUser'] = true;
        if($fvRep->findOneBy(['offre'=>$annonce,'user'=>$userRep->findOneBy(['id'=>$this->get('session')->get('id')])])) $params['isFav'] = true;
        else{
            $params['isFav'] = false;
        }

        if($this->get('session')->has('id')) $params['isLoged'] = true;
        else $params['isLoged'] = false;


        return $this->render("Annonces/offre.html.twig",['annonce'=> $annonce , 'params'=>$params]);
    }







    /**
     *
     * @Route("/annonces/demandes")
     *
     **/
    public function demandes(CategoryRepository $catRep , RegionRepository $regRep , DemandeRepository $annRep, Request $request, EntityManagerInterface $em)
    {
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
                && $rechercheEntt->getCategorie() == null) return $this->redirect('/annonces/demandes');

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



            return $this->redirect('/annonces/demandes' . $strString);
        }



        $strRegion = ""; $strCategory = ""; $strSearch = "";
        if(isset($_GET['region']))$strRegion = $_GET['region'];
        if(isset($_GET['category'])) $strCategory = $_GET['category'];
        if(isset($_GET['search']))$strSearch = $_GET['search'];


        $categorySearchMe = new Category();      $regionSearchMe = new Region();
        $categorySearchMe = null;    $regionSearchMe = null;  $strSearchMe = null;
        if(isset( $_GET['search'])) $strSearch =  $_GET['search'];
        if(isset($_GET['category'])) $categorySearchMe = $em->getRepository(Category::class)->findOneBy(['name'=> $_GET['category']]);
        if(isset($_GET['region'])) $regionSearchMe = $em->getRepository(Region::class)->findOneBy(['name'=>$_GET['region']]);


        $annonces = CustomQuery::getDemandes($em, $strSearch , $categorySearchMe , $regionSearchMe);

        $params = [ 'SearchString' => $strSearch , 'Region' => $strRegion , 'Categorie' => $strCategory , 'annonceSize' => sizeof($annonces) ];


        return $this->render("Annonces/demandes.html.twig",[
            'Params'=> $params,
            'Annonces' => $annonces,
            'Regions'=>$reg,
            'Categories' => $cat,
            'formRecherche' => $form->createView()
        ]);

    }




    /**
     *
     * @Route("/annonces/demandes/{id}")
     *
     **/
    public function demande($id, DemandeRepository $annRep){

        $annonce = $annRep->findOneBy(['id' => $id]);
        if($annonce == null) return $this->render("404.html.twig",[]);
        $params = [];
        if($this->get('session')->has('id')) if($annonce->getUser()->getId() == $this->get('session')->get('id')) $params['isUser'] = true;

        if($this->get('session')->has('id')) $params['isLoged'] = true;
        else $params['isLoged'] = false;

        return $this->render("Annonces/demande.html.twig",['annonce'=> $annonce, 'params'=>$params]);

    }







    /**
     * @Route("/annonces/new")
     **/
    public function newAnnonce(CategoryRepository $catRep, RegionRepository $regRep, Request $request, OffreRepository $offreRepository, DemandeRepository $demandeRepository)
    {

        return $this->redirect('/annonces/new/offre');
    }


    /**
     * @Route("/annonces/new/offre")
     **/
    public function newAOffre(CategoryRepository $catRep, RegionRepository $regRep,UserRepository $userRep, Request $request, OffreRepository $offreRepository, DemandeRepository $demandeRepository, EntityManagerInterface $em)
    {

        if( $this->get('session')->get('id') == null ) return $this->redirect('/');  // si aucune session n'est activée

        $cat = $catRep->findAll();
        $reg = $regRep->findAll();

        $OffreEntt = new OffreEntt();

        $form = $this->createFormBuilder($OffreEntt)
            ->add('category', ChoiceType::class, ['choices' => [ArrayExport::exportCategoryToChoice($cat)][0],'label' => false])
            ->add('title',TextType::class,['label' => false])
            ->add('description',TextareaType::class,['label' => false])
            ->add('price',MoneyType::class,['label' => false])
            ->add('city',TextType::class,['label' => false])
            ->add('address',TextType::class,['label' => false])
            ->add('region',ChoiceType::class, ['choices' => [ArrayExport::exportRegionToChoice($reg)][0],'label' => false])
            ->add('image1',TextareaType::class,['label' => false])
            ->add('image2',TextareaType::class,['label' => false])
            ->add('image3',TextareaType::class,['label' => false])
            ->getForm();

        // TODO : ajouter les images

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $categorieString = $OffreEntt->getCategory();
            $categorie = $catRep->findOneBy(['name'=> $categorieString]);
            if(!isset($categorie)) return $this->render('404.html.twig');

            $regionString = $OffreEntt->getRegion();
            $region = $regRep->findOneBy(['name'=>$regionString]);
            if(!isset($region)) return $this->render('404.html.twig');

            $user = $userRep->findOneBy(['id'=>$this->get('session')->get('id')]);
            if(!isset($user)) return $this->render('404.html.twig');


            $finalOffre = new Offre();
            $finalOffre->setUser($user)
                ->setAddress($OffreEntt->getAddress())
                ->setDate(new \DateTime("now"))
                ->setPrice($OffreEntt->getPrice())
                ->setRegion($region)
                ->setTitle($OffreEntt->getTitle())
                ->setDescription($OffreEntt->getDescription())
                ->setImage1($OffreEntt->getImage1())
                ->setImage2($OffreEntt->getImage2())
                ->setImage3($OffreEntt->getImage3())
                ->setCategory($categorie);

                $em->persist($finalOffre);
                $em->flush();
                return $this->redirect('/annonces/offres');
        }


        return $this->render("Annonces/newOffre.html.twig",['annonceForm' => $form->createView()]);
    }





    /**
     * @Route("/annonces/new/demande")
     **/
    public function newADemande(CategoryRepository $catRep, RegionRepository $regRep, Request $request,UserRepository $userRep, OffreRepository $offreRepository, DemandeRepository $demandeRepository, EntityManagerInterface $em)
    {

        if( $this->get('session')->get('id') == null ) return $this->redirect('/');  // si aucune session n'est activée



        $cat = $catRep->findAll();
        $reg = $regRep->findAll();

        $DemEntt = new DemandeEntt();



        $form = $this->createFormBuilder($DemEntt)
            ->add('category', ChoiceType::class, ['choices' => [ArrayExport::exportCategoryToChoice($cat)][0],'label' => false])
            ->add('title',TextType::class,['label' => false])
            ->add('description',TextareaType::class,['label' => false])
            ->add('region',ChoiceType::class, ['choices' => [ArrayExport::exportRegionToChoice($reg)][0],'label' => false])
            ->getForm();

        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid()){

            $categorieString = $DemEntt->getCategory();
            $categorie = $catRep->findOneBy(['name'=> $categorieString]);
            if(!isset($categorie)) return $this->render('404.html.twig');

            $regionString = $DemEntt->getRegion();
            $region = $regRep->findOneBy(['name'=>$regionString]);
            if(!isset($region)) return $this->render('404.html.twig');

            $user = $userRep->findOneBy(['id'=>$this->get('session')->get('id')]);
            if(!isset($user)) return $this->render('404.html.twig');


            $finalOffre = new Demande();
            $finalOffre->setUser($user)
                ->setDate(new \DateTime("now"))
                ->setRegion($region)
                ->setTitle($DemEntt->getTitle())
                ->setDescription($DemEntt->getDescription())
                ->setImage1("")
                ->setImage2("")
                ->setImage3("")
                ->setPrice(0)
                ->setAddress("")
                ->setCategory($categorie);

            $em->persist($finalOffre);
            $em->flush();
            return $this->redirect('/annonces/demandes');
        }




        return $this->render("Annonces/newDemande.html.twig",['annonceForm' => $form->createView()]);

    }






    /**
     *
     * @Route("/annonces/favoris")
     *
     **/
    public function favAnnonce(CategoryRepository $catRep , RegionRepository $regRep , OffreRepository $annRe, FavoriRepository $fvRep, UserRepository $userRep, EntityManagerInterface $em)
    {

        if( $this->get('session')->get('id') == null ) return $this->redirect('/');  // si aucune session n'est activée

        if(isset($_POST['rmFav'])){
            $fav = $fvRep->findOneBy(['id'=>$_POST['rmFav']]);
            if(isset($fav)){
                $em->remove($fav);
                $em->flush();
            }
        }




        $reg = $regRep->findAll();
        $cat = $catRep->findAll();

        $user = $userRep->findOneBy(['id'=>$this->get('session')->get('id')]);
        $annonces = $fvRep->findBy(['user'=>$user]);

        $params = [ 'annonceSize' => sizeof($annonces) ];

      return $this->render("Annonces/favoris.html.twig",[
            'Params'=> $params,
            'Annonces' => $annonces,
            'Regions'=>$reg,
            'Categories' => $cat]);

    }





    /**
     *
     * @Route("/annonces/buy/{id}")
     *
     **/
    public function acheter($id , OffreRepository $ofrp , EntityManagerInterface $em, FavoriRepository $fvRep ){

        if( $this->get('session')->get('id') == null ) return $this->redirect('/');  // si aucune session n'est activée


        $annonce = $ofrp->findOneBy(['id'=>$id]);

        if(isset($_POST['buy'])){
            $em->remove($annonce);
            $em->flush();
            $off = $fvRep->findOneBy(['offre'=>$annonce]);
            $em->remove($off);
            $em->flush();
            return $this->redirect('/');
        }


        return $this->render("Annonces/buy.html.twig",[
            'annonce' => $annonce
        ]);
    }



    /**
     * @Route("/loginOut")
     **/
    public function loginOut(){
        $this->get('session')->clear();
        return  $this->redirect('/');
    }




    /**
     *
     * @Route("/annonces/offres/edit/{id}")
     *
     **/
    public function editOffre($id, OffreRepository $offRep,UserRepository $userRep, Request $request , CategoryRepository $catRep , RegionRepository $regRep, EntityManagerInterface $em, FavoriRepository $fvRep){

        if( $this->get('session')->get('id') == null ) return $this->redirect('/');  // si aucune session n'est activée


        $offrefinal = $offRep->findOneBy(['id'=>$id]);
        if(!isset($offrefinal)) return $this->redirect('/');
        if($offrefinal->getUser()->getId() !=  $this->get('session')->get('id') )  return $this->redirect('/');


        $cat = $catRep->findAll();
        $reg = $regRep->findAll();



        $OffreEntt = new OffreEntt();
        $OffreEntt->setAddress($offrefinal->getAddress());
        $OffreEntt->setPrice($offrefinal->getId());
        $OffreEntt->setCategory($offrefinal->getCategory()->getName());
        $OffreEntt->setDescription($offrefinal->getDescription());
        $OffreEntt->setTitle($offrefinal->getTitle());
        $OffreEntt->setRegion($offrefinal->getRegion()->getName());
        $OffreEntt->setCity($offrefinal->getCity());

        $form = $this->createFormBuilder($OffreEntt)
            ->add('category', ChoiceType::class, ['choices' => [ArrayExport::exportCategoryToChoice($cat)][0],'label' => false])
            ->add('title',TextType::class,['label' => false])
            ->add('description',TextareaType::class,['label' => false])
            ->add('price',MoneyType::class,['label' => false])
            ->add('city',TextType::class,['label' => false])
            ->add('address',TextType::class,['label' => false])
            ->add('region',ChoiceType::class, ['choices' => [ArrayExport::exportRegionToChoice($reg)][0],'label' => false])
            ->getForm();

        // TODO : ajouter les images

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $categorieString = $OffreEntt->getCategory();
            $categorie = $catRep->findOneBy(['name'=> $categorieString]);
            if(!isset($categorie)) return $this->render('404.html.twig');

            $regionString = $OffreEntt->getRegion();
            $region = $regRep->findOneBy(['name'=>$regionString]);
            if(!isset($region)) return $this->render('404.html.twig');

            $user = $userRep->findOneBy(['id'=>$this->get('session')->get('id')]);
            if(!isset($user)) return $this->render('404.html.twig');

            $offrefinal->setUser($user)
                ->setAddress($OffreEntt->getAddress())
                ->setDate(new \DateTime("now"))
                ->setPrice($OffreEntt->getPrice())
                ->setRegion($region)
                ->setTitle($OffreEntt->getTitle())
                ->setDescription($OffreEntt->getDescription())
                ->setCategory($categorie)
                ->setCity($OffreEntt->getCity());

            $em->persist($offrefinal);
            $em->flush();
            return $this->redirect('/annonces/offres');
        }

        return $this->render('Annonces/editOffre.html.twig', [ 'annonceForm'=> $form->createView()]);

    }



    /**
     *
     * @Route("/annonces/demandes/edit/{id}")
     *
     **/
    public function editDemande($id, DemandeRepository $offRep,UserRepository $userRep, Request $request , CategoryRepository $catRep , RegionRepository $regRep, EntityManagerInterface $em){

        if( $this->get('session')->get('id') == null ) return $this->redirect('/');  // si aucune session n'est activée


        $Demandefinal = $offRep->findOneBy(['id'=>$id]);
        if(!isset($Demandefinal)) return $this->redirect('/');
        if($Demandefinal->getUser()->getId() !=  $this->get('session')->get('id') )  return $this->redirect('/');


        $cat = $catRep->findAll();
        $reg = $regRep->findAll();

        $OffreEntt = new DemandeEntt();
        $OffreEntt->setCategory($Demandefinal->getCategory()->getName());
        $OffreEntt->setDescription($Demandefinal->getDescription());
        $OffreEntt->setTitle($Demandefinal->getTitle());
        $OffreEntt->setRegion($Demandefinal->getRegion()->getName());

        $form = $this->createFormBuilder($OffreEntt)
            ->add('category', ChoiceType::class, ['choices' => [ArrayExport::exportCategoryToChoice($cat)][0],'label' => false])
            ->add('title',TextType::class,['label' => false])
            ->add('description',TextareaType::class,['label' => false])
            ->add('region',ChoiceType::class, ['choices' => [ArrayExport::exportRegionToChoice($reg)][0],'label' => false])
            ->getForm();


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $categorieString = $OffreEntt->getCategory();
            $categorie = $catRep->findOneBy(['name'=> $categorieString]);
            if(!isset($categorie)) return $this->render('404.html.twig');

            $regionString = $OffreEntt->getRegion();
            $region = $regRep->findOneBy(['name'=>$regionString]);
            if(!isset($region)) return $this->render('404.html.twig');

            $user = $userRep->findOneBy(['id'=>$this->get('session')->get('id')]);
            if(!isset($user)) return $this->render('404.html.twig');

            $Demandefinal->setUser($user)
                ->setDate(new \DateTime("now"))
                ->setTitle($OffreEntt->getTitle())
                ->setDescription($OffreEntt->getDescription())
                ->setCategory($categorie);

            $em->persist($Demandefinal);
            $em->flush();
            return $this->redirect('/annonces/demandes');
        }

        return $this->render('Annonces/editDemande.html.twig', [ 'annonceForm'=> $form->createView()]);

    }


}