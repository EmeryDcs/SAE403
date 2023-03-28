<?php
namespace App\Controller;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utilisateur;


class ProfilController extends AbstractController
{
    
    // public function etudiant(): Response {
 
    //     $user = $this->getUser();
    
    //     $nom = $user->getNom();
    //     $prenom = $user->getPrenom();
    //     $email = $user-> getEmail();
    //     return $this->render('profil/etudiant.html.twig', [
    //         'nom' => $nom,
    //         'prenom' => $prenom,
    //         'email' => $email,
    //     ]); 
    // }


    //     return new JsonResponse($data);
   

    public function etudiant(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        // $repository = $this->getDoctrine()->getRepository(Utilisateur::class);
        // $user = $repository->findBy(array('id' => $user->getId()));
        // $user = $repository->findAll();

        $user = $this->getUser();
        // $data = [];

        $data = [
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'email' => $user->getEmail()
        ];

        return new JsonResponse($data);

        // foreach ($users as $user) {
        //     $data[] = [
        //         'nom' => $user->getNom(),
        //         'prenom' => $user->getPrenom(),
        //         'email' => $user->getEmail()
        //     ];
        // }
    }


    public function prof(): Response
    {
        $user = $this->getUser();
    
        $nom = $user->getNom();
        $prenom = $user->getPrenom();
        $email = $user-> getEmail();
        return $this->render('profil/prof.html.twig', [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
        ]); 
                
    }

    public function admin(): Response
    {
        $user = $this->getUser();
    
        $nom = $user->getNom();
        $prenom = $user->getPrenom();
        $email = $user-> getEmail();
        return $this->render('profil/admin.html.twig', [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
        ]); 
                
    }
}

?>