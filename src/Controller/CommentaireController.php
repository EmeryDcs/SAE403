<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Commentaire;
use App\Entity\Projet;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

class CommentaireController extends AbstractController
{
    // Récupère tous les commentaires dans la bdd
    public function commentaires(ManagerRegistry $doctrine, Request $request){
        $repository=$doctrine->getRepository(Commentaire::class)->createQueryBuilder('c') 
        ->select('c.texte, u.nom AS utilisateur_nom, p.nom AS projet_nom')
        ->leftJoin('c.utilisateur', 'u')
        ->leftJoin('c.projet', 'p')
        ->getQuery();
        
        $result = $repository->getArrayResult();
        return new JsonResponse($result);
    }

    // Récupère tous les commentaires d'un même utilisateur grâce à son id
    public function commentaire(EntityManagerInterface $em, $id)
    {
        $qb = $em->createQueryBuilder();

        $qb->select('c.id as commentaire_id', 'c.texte as commentaire_texte', 'p.id as projet_id')
            ->from(Commentaire::class, 'c')
            ->leftJoin('c.utilisateur', 'u')
            ->leftJoin('c.projet', 'p')
            ->where('u.id = :idUser')
            ->setParameter('idUser', $id);

        $query = $qb->getQuery();
        $commentaires = $query->getResult();

        $commentaireData = [];

        foreach ($commentaires as $commentaire) {
            $commentaireData[] = [
                'id' => $commentaire['commentaire_id'],
                'message' => $commentaire['commentaire_texte'],
                'projet_id' => $commentaire['projet_id']
            ];
        }

        return new JsonResponse($commentaireData);
    }

    
    // Récupère tous les commentaires d'un même projet grâce à son id
    public function commentaireprojet(EntityManagerInterface $em, $projetid){
        $qb = $em->createQueryBuilder();
    
        $qb->select('c', 'u')
            ->from(Commentaire::class, 'c')
            ->leftJoin('c.projet', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $projetid);
    
        $query = $qb->getQuery();
        $commentaires = $query->getResult();
    
        $commentaireData = [];
    
        foreach ($commentaires as $commentaire) {
            $commentaireData[] = [
                'id' => $commentaire->getId(),
                'message' => $commentaire->getTexte()
            ];
        }
    
        return new JsonResponse($commentaireData);
    }

    public function addCommentaire(ManagerRegistry $doctrine,Request $request, EntityManagerInterface $entityManager): Response{
        $commentaire = new Commentaire();
        $data = json_decode($request->getContent(), true);
        // $commentaire->setNote($data['note']);
        $repository=$doctrine->getRepository(Projet::class);
        $projet = $repository->findOneBy(['id' => $data['id_projet']]);
        $commentaire->setProjet($projet);
        $commentaire->setTexte($data['texte']);
        $utilisateurRepository = $doctrine->getRepository(Utilisateur::class);
        $utilisateur = $utilisateurRepository->find($data['id_user']);
        $commentaire->setUtilisateur($utilisateur);
        
        $entityManager->persist($commentaire);
        $entityManager->flush();

        return new Response("Commentaire ajoute");
    }


}

?>
