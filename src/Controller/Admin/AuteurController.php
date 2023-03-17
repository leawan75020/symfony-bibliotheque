<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuteurController extends AbstractController
{
    #[Route('/admin/auteur/new', name: 'app_admin_create')]
    public function create(Request $request, AuteurRepository $repository): Response
    {
        if($request->isMethod('POST')){
            //recupérer les champs du formulaire
            $name = $request->request->get('name');
            $description = $request->request->get('description');
            $imageUrl = $request->request->get('imageUrl');

        //créer l'objet auteur avec les champs du form
            $auteur = new Auteur();
            $auteur->setName($name);
            $auteur->setDescription($description);
            $auteur->setImageUrl($imageUrl);

            //enregistrer la auteur dans la BD via le repository
            $repository->save($auteur, true);

            //redirection vers la liste des auteurs
            return $this->redirectToRoute('app_admin_list');
        }
        //affichage de la vue qui contient le formulaire
        return $this->render('admin/auteur/create.html.twig');

        
    }

    #[Route('/admin/auteur/list', name:'app_admin_list')]
    public function list( AuteurRepository $repository) :Response
    {
        //recuperer la liste des auteur da la BD via le repo

        $auteurs = $repository->findAll();
        //afficher la list dans la twig
        return $this->render('admin/auteur/list.html.twig', ['auteurs'=> $auteurs]);
    }

    #[Route('/admin/auteur/{id}/modify', name:'app_auteur_update')]
    public function update( int $id, AuteurRepository $repository, Request $request) :Response
    {
        //recupere la auteur avec l'id 
        $auteur = $repository->find($id);

        //tester si le form est envoyé
        if($request->isMethod('POST')){
            //recuperer les champs du form
            $name= $request->request->get('name');
            $description= $request->request->get('description');
            $imageUrl= $request->request->get('imageUrl');
            //modify $auteur avec les new donnée
            $auteur->setName($name);
            $auteur->setDescription($description);
            $auteur->setImageUrl($imageUrl);

            //enregistrer les donnée dans la BD  via le repo
             $repository->save($auteur, true);
            //redirection vers la liste des auteurs
            return $this->redirectToRoute('app_admin_list');
        }
        //affichage du form de modify
        return $this->render('admin/auteur/update.html.twig',[
             'auteur' => $auteur
        ] );
    } 
    
    #[Route('/admin/auteur/{id}/delete', name:'app_auteur_remove')]
    public function remove( int $id, AuteurRepository $repository) :Response
    {
        //recupere la auteur a del selon l'id 
        $auteur = $repository->find($id);

        //delete la auteur de la BD via le repo
        $repository->remove($auteur, true);
        //redirection vers la liste des auteurs
       
        return $this->redirectToRoute('app_admin_list');

    }



}