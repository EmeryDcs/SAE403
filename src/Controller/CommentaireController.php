<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
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
}

?>
