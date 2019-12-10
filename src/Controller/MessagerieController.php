<?php

namespace App\Controller;


use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\MessagerieRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Service\MessagerieQuery;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use App\Entity\newConvEntt;
use App\Entity\Messagerie;
use App\Entity\MessagerieMsgEntt;

class MessagerieController extends AbstractController
{
    /**
 * @Route("/messagerie", name="messagerie")
 *
 * on recupere toutes les conversations
 */
    public function messagerie(UserRepository $userRep, EntityManagerInterface $em)
    {
        if( $this->get('session')->get('id') == null ) return $this->redirect('/');  // si aucune session n'est activée

        $user = $userRep->findOneBy(['id'=>$this->get('session')->get('id')]);
        $messageries = MessagerieQuery::getAllMessageries($user,$em);

        return $this->render('messagerie/messageries.html.twig',[
            'messageries'=>$messageries
        ]);
    }




    /**
     * @Route("/messagerie/NewConversation", name="newConv")
     *
     * on crées une nouvelle conversation
     */
    public function newConv(UserRepository $userRep, EntityManagerInterface $em, Request $request)
    {
        if( $this->get('session')->get('id') == null ) return $this->redirect('/');  // si aucune session n'est activée

        $enttConv = new newConvEntt();


        $form = $this->createFormBuilder($enttConv)
            ->add('name',TextType::class,['label' => false])
            ->add('firstName',TextType::class,['label' => false])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $userRep->findOneBy(['name'=>$enttConv->getName() , 'first_name'=>$enttConv->getFirstName()]);
            $logedUser = $userRep->findOneBy(['id'=>$this->get('session')->get('id')]);
            if($user->getId() == $logedUser->getId()) return $this->render('messagerie/newConversation.html.twig',['newConv'=>$form->createView() , 'error' => "Impossible de creer une conversation avec vous même "]);
            if(!isset($user))  return $this->render('messagerie/newConversation.html.twig',['newConv'=>$form->createView() , 'error' => "Impossible de trouver un utilisateur avec cette identitée"]);
            $conversations = MessagerieQuery::getAllMessageries($logedUser,$em);
            foreach ($conversations as $item) {
                print_r($item);
                if($item['userName'] == $enttConv->getName() && $item['userFirstName'] == $enttConv->getFirstName()) return $this->redirect('/messagerie/'.$item['id']);
            }
            $newMessagerie = new Messagerie();
            $newMessagerie->setUserOne($user);
            $newMessagerie->setUserTwo($logedUser);
            $em->persist($newMessagerie);
            $em->flush();

            return $this->redirect('/messagerie/'.$newMessagerie->getId());

        }

        return $this->render('messagerie/newConversation.html.twig',['newConv'=>$form->createView()]);
    }








    /**
     * @Route("/messagerie/{id}", name="messagerieUser")
     *
     * on recupere toutes les conversations
     */
    public function messagerieUser($id,UserRepository $userRep,MessageRepository $messageRepository,MessagerieRepository $messagerieRepository, EntityManagerInterface $em, Request $request)
    {
        if( $this->get('session')->get('id') == null ) return $this->redirect('/');  // si aucune session n'est activée


        // on regarde si la messagerie est valide
        $messagerie = $messagerieRepository->findOneBy(['id'=>$id]);
        if(!isset($messagerie)) return $this->redirect('/messagerie');
        if($messagerie->getUserOne()->getId() != $this->get('session')->get('id') && $messagerie->getUserTwo()->getId() != $this->get('session')->get('id') )return $this->redirect('/messagerie');

        // on crées les entitées
        $userMe = $userRep->findOneBy(['id'=> $this->get('session')->get('id')]);
        $userHe = new User();
        if($messagerie->getUserOne()->getId() == $userMe->getId())$userHe = $messagerie->getUserTwo();
        else if($messagerie->getUserTwo()->getId() == $userMe->getId())$userHe = $messagerie->getUserOne();

        //php bin/console doctrine:migrations:migrate
        $messages = $messageRepository->findBy(['Messagerie'=>$messagerie]);

        $params =[];
        $params['userme'] = $userMe;



        $msgEntt = new MessagerieMsgEntt();

        $form = $this->createFormBuilder($msgEntt)
            ->add('message',TextType::class,['label' => false])
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $msg = new Message();
            $msg->setMessage($msgEntt->getMessage());
            $msg->setUserFrom($userMe);
            $msg->setUserTo($userHe);
            $msg->setDate(new \DateTime("now"));
            $msg->setMessagerie($messagerie);
            $em->persist($msg);
            $em->flush();
            return $this->redirect($request->getUri());
        }

        return $this->render('messagerie/messagerie.html.twig',['messages' => $messages , 'params'=>$params , 'formMessage'=>$form->createView()]);
    }






    /**
     * @Route("/contacter/{id}", name="contacter")
     *
     * on recupere toutes les conversations
     */
    public function contacter($id , UserRepository $userRep, MessagerieRepository $msgRep, EntityManagerInterface $em){

        if( $this->get('session')->get('id') == null ) return $this->redirect('/');  // si aucune session n'est activée

        $userMe = $userRep->findOneBy(['id'=> $this->get('session')->get('id')]);
        $userHe = $userRep->findOneBy(['id'=>$id]);

        $messageries = MessagerieQuery::getAllMessageries($userMe,$em);
        foreach ($messageries as $value){
            if($value['userId']== $userHe->getId())return $this->redirect('/messagerie/'.$value['id']);
        }

        // on doit creer une nouvelle messagerie avec userMe et userHe
        $newMessagerie = new Messagerie();
        $newMessagerie->setUserOne($userMe);
        $newMessagerie->setUserTwo($userHe);
        $em->persist($newMessagerie);
        $em->flush();

        return $this->redirect('/messagerie/'.$newMessagerie->getId());
    }






}



























