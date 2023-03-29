<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Competence;
use App\Form\CommentaireType;

class CompetencesController extends AbstractController
{
    public function competences(ManagerRegistry $doctrine, Request $request){
        $repository=$doctrine->getRepository(Competence::class)->createQueryBuilder('u') 
        ->getQuery(); 
        $result = $repository->getArrayResult();
        return new JsonResponse($result);
    }
 
}