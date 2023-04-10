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

    public function utilisateurid(EntityManagerInterface $em, $id)
    {
        $qb = $em->createQueryBuilder();

        $qb->select('u')
            ->from(Utilisateur::class, 'u')
            ->where('u.id LIKE :id')
            ->setParameter('id', $id);

        $query = $qb->getQuery();
        $users = $query->getArrayResult();

        return new JsonResponse($users);
    }

    public function getusername(EntityManagerInterface $em,string $email): JsonResponse
    {
        $qb = $em->createQueryBuilder();

        $qb->select('u')
            ->from(Utilisateur::class, 'u')
            ->where('u.email LIKE :email')
            ->setParameter('email', $email);

        $query = $qb->getQuery();
        $user = $query->getArrayResult();

        return new JsonResponse($user);
    }

}

?>
