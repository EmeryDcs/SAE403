<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Projet;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Entity\Utilisateur;

class PortfolioController extends AbstractController
{
    //affiche TOUS les projets
    public function accueil(ManagerRegistry $doctrine, Request $request){
        //Ici on récupère l'entiereté du talbeau Projet
        $repository = $doctrine->getRepository(Projet::class);
        $projets = $repository->findAll();

        //On récupère les compétences liées à un projet dans un tableau associatif projet->competence
        $competences = [];
        //On fait la même que compétences mais pour les ac.
        $ac = [];
        //De même pour les commentaires
        $commentairesProjet = [];
        //On deJSON la variable domaine et on envoie les données correspondantes
        $domaines = [];
        //commentaires
        $commentaires = [];
        $tabFormCommentaire = [];
        foreach ($projets as $projet){
            $competences[$projet->getId()] = $projet->getCompetences();
            $ac[$projet->getId()] = $projet->getAc();
            $domaines[$projet->getId()] = unserialize($projet->getDomaine());
            $commentairesProjet[$projet->getId()] = $projet->getCommentaires();
            foreach ($commentairesProjet as $chercheAuteurCommentaire){
                foreach ($chercheAuteurCommentaire as $value){
                    $auteurCommentaire[$value->getId()]=$value->getUtilisateur();
                }
            }

            if ($this->getUser() && in_array("ROLE_PROF",$this->getUser()->getRoles())){
                //Je stocke mon commentaire dans un tableau avec pour clé l'id du projet
                $commentaires[$projet->getId()] = new Commentaire();

                //Je crée mon formulaire lié
                $formCommentaire = $this->createForm(CommentaireType::class, $commentaires[$projet->getId()]);
                $formCommentaire->get('projet')->setData($projet->getId());

                //Je stocke ce formulaire dans un tableau associatif avec l'id du projet en clé
                $tabFormCommentaire[$projet->getId()] = $formCommentaire;
                $tabFormCommentaire[$projet->getId()]->handleRequest($request);

                //Je stocke la vue de tous mes formulaires pour les afficher à la fin
                $vuesCommentaires[$projet->getId()] = $tabFormCommentaire[$projet->getId()]->createView();
        
                if ($tabFormCommentaire[$projet->getId()]->isSubmitted() && $tabFormCommentaire[$projet->getId()]->isValid()){
                    $user=$this->getUser();
                    $commentaires[$projet->getId()]->setUtilisateur($user);

                    //Je stocke le projet courant dans mon commentaire
                    $commentaires[$projet->getId()]->setProjet(
                        $repository->findOneBy(['id'=>$formCommentaire->get('projet')->getData()])
                    );
        
                    $em = $doctrine->getManager();
                    $em->persist( $commentaires[$projet->getId()]);
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

        // if (isset($formCommentaire)){
        //     return $this->render(
        //         'portfolio.html.twig',
        //         [
        //             'projets'=>$projets,
        //             'competences'=>$competences,
        //             'ac'=>$ac,
        //             'domaines'=>$domaines,
        //             'video'=>$embed_html,
        //             'commentaires'=>$commentairesProjet,
        //             'auteurCommentaire'=>$auteurCommentaire,
        //             'formCommentaire'=>$vuesCommentaires,
        //         ]
        //     );
        // } else {
        //     return $this->render(
        //         'portfolio.html.twig',
        //         [
        //             'projets'=>$projets,
        //             'competences'=>$competences,
        //             'ac'=>$ac,
        //             'domaines'=>$domaines,
        //             'video'=>$embed_html,
        //         ]
        //     );
        // }

        if (isset($formCommentaire)){
            $data = [
                'projets'=>$projets,
                'competences'=>$competences,
                'ac'=>$ac,
                'domaines'=>$domaines,
                'video'=>$embed_html,
                'commentaires'=>$commentairesProjet,
                'auteurCommentaire'=>$auteurCommentaire,
                'formCommentaire'=>$vuesCommentaires
            ];
        }else{
            $data = [
                'projets'=>$projets,
                'competences'=>$competences,
                'ac'=>$ac,
                'domaines'=>$domaines,
                'video'=>$embed_html
            ];
        }
        return new JsonResponse($data);
    }   

    // affiche TOUS les projets d'un utilisateur
    public function afficheProjetUtilisateur(ManagerRegistry $doctrine, Request $request, $id){
        $repositoryProjets = $doctrine->getRepository(Projet::class);
        $projets = $repositoryProjets->findBy(['utilisateur'=>$id]);
        $repositoryUtilisateurs = $doctrine->getRepository(Utilisateur::class);
        $nomUtilisateur = $repositoryUtilisateurs->findOneBy(['id'=>$id]);

        if ($projets != []){
            //On récupère les compétences liées à un projet dans un tableau associatif projet->competence
            $competences = [];
            //On fait la même que compétences mais pour les ac.
            $ac = [];
            //De même pour les commentaires
            $commentairesProjet = [];
            //On deJSON la variable domaine et on envoie les données correspondantes
            $domaines = [];
            //commentaires
            $commentaires = [];
            $tabFormCommentaire = [];
            foreach ($projets as $projet){
                $competences[$projet->getId()] = $projet->getCompetences();
                $ac[$projet->getId()] = $projet->getAc();
                $domaines[$projet->getId()] = unserialize($projet->getDomaine());
                $commentairesProjet[$projet->getId()] = $projet->getCommentaires();
                foreach ($commentairesProjet as $chercheAuteurCommentaire){
                    foreach ($chercheAuteurCommentaire as $value){
                        $auteurCommentaire[$value->getId()]=$value->getUtilisateur();
                    }
                }

                if ($this->getUser() && in_array("ROLE_PROF",$this->getUser()->getRoles())){
                    //Je stocke mon commentaire dans un tableau avec pour clé l'id du projet
                    $commentaires[$projet->getId()] = new Commentaire();
    
                    //Je crée mon formulaire lié
                    $formCommentaire = $this->createForm(CommentaireType::class, $commentaires[$projet->getId()]);
                    $formCommentaire->get('projet')->setData($projet->getId());
    
                    //Je stocke ce formulaire dans un tableau associatif avec l'id du projet en clé
                    $tabFormCommentaire[$projet->getId()] = $formCommentaire;
                    $tabFormCommentaire[$projet->getId()]->handleRequest($request);
    
                    //Je stocke la vue de tous mes formulaires pour les afficher à la fin
                    $vuesCommentaires[$projet->getId()] = $tabFormCommentaire[$projet->getId()]->createView();
            
                    if ($tabFormCommentaire[$projet->getId()]->isSubmitted() && $tabFormCommentaire[$projet->getId()]->isValid()){
                        $user=$this->getUser();
                        $commentaires[$projet->getId()]->setUtilisateur($user);
    
                        //Je stocke le projet courant dans mon commentaire
                        $commentaires[$projet->getId()]->setProjet(
                            $repository->findOneBy(['id'=>$formCommentaire->get('projet')->getData()])
                        );
            
                        $em = $doctrine->getManager();
                        $em->persist( $commentaires[$projet->getId()]);
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
                    'portfolioetudiant.html.twig', 
                    [
                        'projets'=>$projets,
                        'competences'=>$competences,
                        'ac'=>$ac,
                        'domaines'=>$domaines,
                        'video'=>$embed_html,
                        'nom'=>$nomUtilisateur->getNom(),
                        'prenom'=>$nomUtilisateur->getPrenom(),
                        'commentaires'=>$commentairesProjet,
                        'auteurCommentaire'=>$auteurCommentaire,
                        'formCommentaire'=>$vuesCommentaires,
                    ]
                );
            } else {
                return $this->render(
                    'portfolioetudiant.html.twig', 
                    [
                        'projets'=>$projets,
                        'competences'=>$competences,
                        'ac'=>$ac,
                        'domaines'=>$domaines,
                        'video'=>$embed_html,
                        'nom'=>$nomUtilisateur->getNom(),
                        'prenom'=>$nomUtilisateur->getPrenom(),
                        'commentaires'=>$commentairesProjet,
                        'auteurCommentaire'=>$auteurCommentaire,
                    ]
                );
            }
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