<?php

// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Projet;


class ProjetController extends AbstractController
{
    public function projet(ManagerRegistry $doctrine, Request $request){
        $repository=$doctrine->getRepository(Projet::class)->createQueryBuilder('u') 
        ->getQuery(); 
        $result = $repository->getArrayResult();
        return new JsonResponse($result);
    }
}