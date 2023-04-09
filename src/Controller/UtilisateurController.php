<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Utilisateur;

class UtilisateurController extends AbstractController
{
    // public function utilisateur(ManagerRegistry $doctrine, Request $request){
    //     $repository=$doctrine->getRepository(Utilisateur::class)->createQueryBuilder('u') 
    //     ->getQuery(); 
    //     $result = $repository->getArrayResult();
    //     return new JsonResponse($result);
    // }

    public function utilisateur(EntityManagerInterface $em)
    {
        $qb = $em->createQueryBuilder();

        $qb->select('u')
            ->from(Utilisateur::class, 'u')
            ->where('u.roles NOT LIKE :role')
            ->setParameter('role', '%"ROLE_ADMIN"%');

        $query = $qb->getQuery();
        $users = $query->getArrayResult();

        return new JsonResponse($users);
    }

    public function utilisateurprojet(EntityManagerInterface $em)
    {
        $qb = $em->createQueryBuilder();

        $qb->select('u')
            ->from(Utilisateur::class, 'u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"ROLE_USER"%');

        $query = $qb->getQuery();
        $users = $query->getArrayResult();

        return new JsonResponse($users);
    }


    // public function getusername(EntityManagerInterface $entityManager, $email): JsonResponse
    // {
    //     $qb = $entityManager->createQueryBuilder();

    //     $qb->select('u.nom', 'u.prenom')
    //         ->from(Utilisateur::class, 'u')
    //         ->where('u.email = :email')
    //         ->setParameter('email', $email);

    //     $result = $qb->getQuery()->getOneOrNullResult();

    //     if (!$result) {
    //         return new JsonResponse(['error' => 'User not found.'], 404);
    //     }

    //     return new JsonResponse($result);
    // }

    public function getusername(EntityManagerInterface $entityManager,string $email): JsonResponse
    {
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);

        if (!$utilisateur) {
            return new JsonResponse(['error' => 'User not found.'], 404);
        }

        $nom = $utilisateur->getNom();
        $prenom = $utilisateur->getPrenom();

        return new JsonResponse([['nom' => $nom, 'prenom' => $prenom]]);
    }

}

?>
