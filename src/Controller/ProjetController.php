<?php

// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Projet;
use App\Entity\Commentaire;
use App\Form\ProjetFormType;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProjetController extends AbstractController
{
    public function formProjet(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getUser();
        
        $projet = new Projet(); //On crée un objet image qui prendra les valeurs du formulaire
        $form = $this->createForm(ProjetFormType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('photo')->getData(); //On récupère la variable url du formulaire
            /** @var UploadedFile $brochureFile */
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename); 
                //(pour renommer le fichier, utilise slug
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                // le move_uploaded_files()
                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ...gérer les pbs d'upload ici
                }
                // stocker le nom de l'image dans le champ que l'on vient de créer
                $projet->setPhoto($newFilename);
            }

            $domaine = $form->get('domaine')->getData();
            $domaine_string = serialize($domaine);
            $projet->setDomaine($domaine_string);
            $projet->setUtilisateur($user);

            $em = $doctrine->getManager();
            $em->persist($projet);
            $em->flush();

            return $this->redirectToRoute('portfolio');
        } 

        return $this->render(
            'addProjet.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}