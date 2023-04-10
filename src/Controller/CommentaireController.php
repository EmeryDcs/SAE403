<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Commentaire;
use App\Entity\Projet;
use App\Entity\Utilisateur;

class CommentaireController extends AbstractController
{
    public function commentaire(ManagerRegistry $doctrine, Request $request){
        $repository=$doctrine->getRepository(Commentaire::class)->createQueryBuilder('c') 
        ->select('c.texte, u.nom AS utilisateur_nom, p.nom AS projet_nom')
        ->leftJoin('c.utilisateur', 'u')
        ->leftJoin('c.projet', 'p')
        ->getQuery();
        
        $result = $repository->getArrayResult();
        return new JsonResponse($result);


    }

    public function addCommentaire(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response{
        $commentaire = new Commentaire();
        $data = json_decode($request->getContent(), true);
        $commentaire->setNote($data['note']);

        $repository=$doctrine->getRepository(Projet::class);
        $projet = $repository->findOneBy($data['id_projet']);
        $commentaire->setProjet($projet);

        $commentaire->setTexte($data['texte']);
        $commentaire->setUtilisateur($data['id_user']); //à changer par l'utilisateur courant du coup
        
        $entityManager->persist($commentaire);
        $entityManager->flush();

        return new Response('Commentaire ajoute');
    }
}

?>
