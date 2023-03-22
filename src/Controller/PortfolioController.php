<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Projet;
use App\Entity\Commentaire;
use App\Form\CommentaireType;

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
        //commentaires
        $commentaires = [];
        $tabFormCommentaire = [];
        foreach ($projets as $projet){
            $competences[$projet->getId()] = $projet->getCompetences();
            $ac[$projet->getId()] = $projet->getAc();
            $domaines[$projet->getId()] = unserialize($projet->getDomaine());

            if ($this->getUser() && in_array("ROLE_PROF",$this->getUser()->getRoles())){
                $commentaire = new Commentaire();
                $formCommentaire = $this->createForm(CommentaireType::class, $commentaire);
                $formCommentaire->handleRequest($request);

                $tabFormCommentaire[$projet->getId()] = $formCommentaire;
                $tabIdProjet[$projet->getId()] = $projet->getId();

                $commentaires[$projet->getId()] = $formCommentaire->createView();
        
                if ($tabFormCommentaire[$projet->getId()]->isSubmitted() && $tabFormCommentaire[$projet->getId()]->isValid()){
                    $user=$this->getUser();
                    $commentaire->setUtilisateur($user);

                    $projetCommentaire = $repository->findOneBy(['id'=>$tabIdProjet[$projet->getId()]]);

                    $commentaire->setProjet($projetCommentaire);
        
                    $em = $doctrine->getManager();
                    $em->persist($commentaire);
                    $em->flush();

                    return $this->redirectToRoute('accueil');
                }
            }
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

        if (isset($formCommentaire)){
            return $this->render(
                'portfolio.html.twig',
                [
                    'projets'=>$projets,
                    'competences'=>$competences,
                    'ac'=>$ac,
                    'domaines'=>$domaines,
                    'video'=>$embed_html,
                    'formCommentaire'=>$commentaires,
                ]
            );
        } else {
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
    }   

    public function afficheProjetUtilisateur(ManagerRegistry $doctrine, Request $request, $id){
        $repositoryProjets = $doctrine->getRepository(Projet::class);
        $projets = $repositoryProjets->findBy(['utilisateur'=>$id]);

        if ($projets != []){
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
                'portfolioetudiant.html.twig', 
                [
                    'projets'=>$projets,
                    'competences'=>$competences,
                    'ac'=>$ac,
                    'domaines'=>$domaines,
                    'video'=>$embed_html,
                ]
            );
        } else {
            return $this->redirectToRoute('accueil');
        }
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