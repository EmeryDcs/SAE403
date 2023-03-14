<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Utilisateur;

class ProfilController extends AbstractController
{
    // public function etudiant(TokenInterface $token): Response
    // {
        
    //     $user = $token->getUser();
    //     $nom = $user->getNom();
    //     $prenom = $user->getPrenom();
    //     $email = $user-> getEmail();
    //     return $this->render('profil/profil.html.twig', [
    //         'nom' => $nom,
    //         'prenom' => $prenom,
    //         'email' => $email,
    //     ]); 
                
    // }

    public function etudiant(): Response {
 
        $user = $this->getUser();
    
        $nom = $user->getNom();
        $prenom = $user->getPrenom();
        $email = $user-> getEmail();
        return $this->render('profil/etudiant.html.twig', [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
        ]); 
        
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