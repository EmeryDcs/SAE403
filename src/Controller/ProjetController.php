<?php

// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Projet;
use App\Entity\Utilisateur;


class ProjetController extends AbstractController
{
    public function allprojet(ManagerRegistry $doctrine){
        $repository=$doctrine->getRepository(Projet::class)->createQueryBuilder('u') 
        ->getQuery(); 
        $result = $repository->getArrayResult();
        return new JsonResponse($result);
    }

    public function projet(EntityManagerInterface $em, $id){
        $qb = $em->createQueryBuilder();

        $qb->select('p', 'u')
            ->from(Projet::class, 'p')
            ->leftJoin('p.utilisateur', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id);

        $query = $qb->getQuery();
        $projets = $query->getResult();

        $projetsAvecUtilisateurs = [];
        foreach ($projets as $projet) {
            $utilisateur = $projet->getUtilisateur();
            if ($utilisateur) {
                $projetAvecUtilisateur = [
                    'id' => $projet->getId(),
                    'nom' => $projet->getNom(),
                    'description' => $projet->getDescription(),
                    'utilisateur' => [
                        'id' => $utilisateur->getId(),
                        'prenom' => $utilisateur->getPrenom(),
                        'nom' => $utilisateur->getNom(),
                        'email' => $utilisateur->getEmail(),
                    ],
                ];
                $projetsAvecUtilisateurs[] = $projetAvecUtilisateur;
            }
        }

        return new JsonResponse($projetsAvecUtilisateurs);
    }
}