<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Projet;

class PortfolioController extends AbstractController
{
    public function accueil(ManagerRegistry $doctrine, Request $request){
        //Ici on récupère l'entiereté du talbeau Projet
        $repository = $doctrine->getRepository(Projet::class);
        $projets = $repository->findAll();

        //On récupère les compétences liées à un projet dans un tableau associatif projet->competence
        $competences = [];
        //On fait la même que compétences mais pour les ac.
        $ac = [];
        //On deJSON la variable domaine et on envoie les données correspondantes
        $domaines = [];
        foreach ($projets as $projet){
            $competences[$projet->getId()] = $projet->getCompetences();
            $ac[$projet->getId()] = $projet->getAc();
            $domaines[$projet->getId()] = unserialize($projet->getDomaine());
        }

        return $this->render(
            'portfolio.html.twig',
            [
                'projets'=>$projets,
                'competences'=>$competences,
                'ac'=>$ac,
                'domaines'=>$domaines,
            ]
        );
    }   
}