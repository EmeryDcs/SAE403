<?php

// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use App\Entity\Utilisateur;
use App\Entity\Projet;
use App\Entity\Commentaire;

class DeleteController extends AbstractController
{
    public function delete(EntityManagerInterface $entityManager, Request $request, $id){
        
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);
        
        if (!$user) {
            throw $this->createNotFoundException('Impossible de trouver l\'entité avec l\'ID '.$id);
        }
        if($user->getRoles() == 'ROLE_USER'){
            // Créer une requête DQL pour sélectionner tous les projets ayant une clé étrangère utilisateur_id de 42
            $dql = "DELETE FROM App\Entity\Projet p WHERE p.utilisateur = " . $id;
            $projet = $entityManager->createQuery($dql);

            // Exécuter la requête pour supprimer les projets
            $projet->execute();
        }
        if($user->getRoles() == 'ROLE_PROF'){
            // Créer une requête DQL pour sélectionner tous les projets ayant une clé étrangère utilisateur_id de 42
            $dql = "DELETE FROM App\Entity\Commentaire p WHERE p.commentaire = " . $id;
            $comm = $entityManager->createQuery($dql);

            // Exécuter la requête pour supprimer les projets
            $comm->execute();
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return new Response('Entité supprimée avec succès');

    }

    public function deleteprojet(EntityManagerInterface $entityManager, Request $request, $id){
        $projet = $entityManager->getRepository(Projet::class)->find($id);
        
        if (!$projet) {
            throw $this->createNotFoundException('Impossible de trouver l\'entité avec l\'ID '.$id);
        }

        $entityManager->remove($projet);
        $entityManager->flush();

        return new Response('Entité supprimée avec succès');

    }

    public function deletecomm(EntityManagerInterface $entityManager, Request $request, $id){
        $comm = $entityManager->getRepository(Commentaire::class)->find($id);
        
        if (!$comm) {
            throw $this->createNotFoundException('Impossible de trouver l\'entité avec l\'ID '.$id);
        }

        $entityManager->remove($comm);
        $entityManager->flush();

        return new Response('Entité supprimée avec succès');

    }
}