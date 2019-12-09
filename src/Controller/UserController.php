<?php


namespace App\Controller;

use App\Entity\RegisterEntt;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;

use App\Entity\ChangePasswordEntt;
use App\Entity\EditProfileEntt;
use App\Entity\User;
use App\Entity\LoginEntt;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{


    /**
     *   On affiche le formulaire pour se login
     *
     * @Route("/user/login" ,name="login")
     *
     **/
    public function login(Request $request, UserRepository $userRep)
    {

        $logEntt = new LoginEntt();

        $form = $this->createFormBuilder($logEntt)
                ->add('email', EmailType::class)
                ->add('password',PasswordType::class)
                ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $userRep->findOneBy(['email'=>$logEntt->getEmail()]);
            if(isset($user)) {
                if($logEntt->getPassword() == $user->getPassword()){
                    $session = new Session();
                    $session->start();
                    $session->set('name', $user->getName());
                    $session->set('id', $user->getId());
                    return $this->redirect('/');
                }
                else{
                    return $this->render("user/login.html.twig",['loginForm' => $form->createView() , 'error' => "Mauvais mot de passe"]);
                }
            }
            else{
                return $this->render("user/login.html.twig",['loginForm' => $form->createView() , 'error' => "Cet email n'existe pas"]);
            }
        }
        return $this->render("user/login.html.twig",['loginForm' => $form->createView()]);
    }


    /**
     *   On affiche le formulaire pour se register
     *
     * @Route("/user/register" ,name="register")
     *
     **/
    public function register(Request $request, UserRepository $userRep, EntityManagerInterface $em)
    {
        $registerEntt = new RegisterEntt();
        $user = new User();

        $form =  $this->createFormBuilder($registerEntt)
            ->add('nom', TextType::class,['label' => false])
            ->add('prenom',TextType::class,['label' => false])
            ->add('mail' ,TextType::class,['label' => false])
            ->add('mdp' ,PasswordType::class,['label' => false])
            ->add('mdpConfirm' ,PasswordType::class,['label' => false])
            ->add('accept' ,CheckboxType::class,['label' => false])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($registerEntt->getAccept()){
                $userDb = $userRep->findOneBy(['email'=>$registerEntt->getMail()]);
                if(isset($userDb)) return $this->render("user/register.html.twig",['formRegister' => $form->createView() , 'error'=>"Cet email existe deja"]);

                if($registerEntt->getMdp() == $registerEntt->getMdpConfirm()){
                    $user->setName($registerEntt->getNom())
                        ->setFirstName($registerEntt->getPrenom())
                        ->setEmail($registerEntt->getMail())
                        ->setPassword($registerEntt->getMdp())
                        ->setInscription( new \DateTime("now"))
                        ->setPasswordId("");
                    $em->persist($user);
                    $em->flush();
                    return $this->redirect('/user/profile');
                }
                else{
                    return $this->render("user/register.html.twig",['formRegister' => $form->createView() , 'error'=>"Les deux mots de passes ne correspondent pas "]);
                }
            }
            else  return $this->render("user/register.html.twig",['formRegister' => $form->createView() , 'error'=>"Vous devez accepter les conditions"]);
        }

        return $this->render("user/register.html.twig", ['formRegister' => $form->createView()]);
    }

    /**
     *   On affiche les conditions de vente
     *
     * @Route("/doc/cgv")
     *
     **/
    public function cgv(){
        return $this->render("doc/cgv.html.twig",[]);
    }

    /**
     *   On affiche les conditions de d'utilisation
     *
     * @Route("/doc/cgu")
     *
     **/
    public function cgu(){
        return $this->render("doc/cgu.html.twig",[]);
    }


    /**
     *   On change le mot de passe
     *
     * @Route("/user/lost-password")
     *
     **/
    public function reset_password(Request $request ,UserRepository $userRep , EntityManagerInterface $em){


        $enttChangePassword = new ChangePasswordEntt();
        $form = $this->createFormBuilder($enttChangePassword)
            ->add('email', EmailType::class,['label' => false])
            ->add('emailTwo', EmailType::class,['label' => false])
            ->add('password', PasswordType::class,['label' => false])
            ->add('passwordTwo', PasswordType::class,['label' => false])
            ->getForm();

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            if($enttChangePassword->getEmail() != $enttChangePassword->getEmailTwo() )return $this->render("user/reset-password.html.twig",['error'=>"Les deux emails ne correspondent pas" , 'formMdp'=>$form->createView()]);
            if($enttChangePassword->getPassword() != $enttChangePassword->getPasswordTwo()) return $this->render("user/reset-password.html.twig",['error'=>"Les deux mots de passe ne correspondent pas", 'formMdp'=>$form->createView()]);

            $user = $userRep->findOneBy(['email'=>$enttChangePassword->getEmail()]);
            if(!isset($user)) return $this->render("user/reset-password.html.twig",['error'=>"Aucun compte n'est enregistré avec ce mot de passe" , 'formMdp'=>$form->createView()]);

            $user->setPassword($enttChangePassword->getPassword());
            $em->persist($user);
            $em->flush();
            return $this->redirect('/user/login');

        }




        return $this->render("user/reset-password.html.twig",['formMdp'=>$form->createView()]);
    }







    /**
     *   On change le mot de passe
     *
     * @Route("/user/profile")
     *
     **/
    public function profile(UserRepository $userRep){


        if(isset($_GET['name']) &&  isset($_GET['firstname'])  ){
            $user = $userRep->findOneBy(['name'=> $_GET['name'] , 'first_name'=> $_GET['firstname']]);

            if($user == null){
                return $this->render("404.html.twig",[]);
            }
            return $this->render("user/profile_user.html.twig",['User'=>$user]);
        }
        else{
            if( $this->get('session')->get('id') == null ) return $this->redirect('/');  // si aucune session n'est activée

            $user = $userRep->findOneBy(['id' => $this->get('session')->get('id')]);

            return $this->render("user/profile.html.twig",['user'=>$user]);
        }


    }


    /**
     *   On change le mot de passe
     *
     * @Route("/user/edit_profile")
     *
     **/
    public function edit_profile(UserRepository $userRep , Request $request , EntityManagerInterface $em){

        if( $this->get('session')->get('id') == null ) return $this->redirect('/');  // si aucune session n'est activée


        $user = $userRep->findOneBy(['id' => $this->get('session')->get('id')]);
        $editProfileEntt = new EditProfileEntt();
        $editProfileEntt->setEmail($user->getEmail());
        $editProfileEntt->setAddress($user->getAddress());
        $editProfileEntt->setCity($user->getCity());
        $editProfileEntt->setPhone($user->getPhone());


        $form =  $this->createFormBuilder($editProfileEntt)
            ->add('email', TextType::class,['label' => false])
            ->add('address',TextType::class,['label' => false])
            ->add('city' ,TextType::class,['label' => false])
            ->add('phone' ,TextType::class,['label' => false])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user->setEmail($editProfileEntt->getEmail())
                ->setAddress($editProfileEntt->getAddress())
                ->setCity($editProfileEntt->getCity())
                ->setPhone($editProfileEntt->getPhone())
            ;
            $em->persist($user);
            $em->flush();
            return $this->redirect('/user/profile');
        }




        return $this->render("user/edit_profile.html.twig",['user'=>$form->createView()]);
    }











}