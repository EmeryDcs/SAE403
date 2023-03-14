<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Projet;
use App\Entity\Commentaire;

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

        //On change le lien d'une vidéo Youtube en une balise HTML :
        $url = $projet->getVideo(); // URL de la vidéo YouTube
        $video_id = ''; // Initialisez la variable pour stocker l'identifiant de la vidéo

        // Extraire l'identifiant de la vidéo à partir de l'URL
        parse_str(parse_url($url, PHP_URL_QUERY), $query_params);
        if (isset($query_params['v'])) {
            $video_id = $query_params['v'];
        }

        // Construire la balise HTML d'intégration
        $embed_html = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allowfullscreen></iframe>';


        return $this->render(
            'portfolio.html.twig',
            [
                'projets'=>$projets,
                'competences'=>$competences,
                'ac'=>$ac,
                'domaines'=>$domaines,
                'video'=>$embed_html,
            ]
        );
    }   
    
    // public function ajoutCommentaire(ManagerRegistry $doctrine, Request $request){
    //     $commentaire = new Commentaire();
    //     $formCommentaire = $this->createForm(CommentaireType::class, $commentaire);
    //     $formCommentaire->handleRequest($request);

    //     return $this->render(
    //         'portfolio.html.twig',
    //         [
    //             'formCommentaire' => $formCommentaire->createView(),
    //         ]
    //     );
    // }
}