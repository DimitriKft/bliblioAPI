<?php

namespace App\Controller;


use App\Repository\GenreRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;



class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="api_genres", methods={"GET"})
     */
    public function list(GenreRepository $repo, SerializerInterface $serializer)
    {
        $genres   = $repo->findAll();
        $resultat = $serializer->serialize($genres,
            'json',
            [
            'groups' =>['listGenreFull']
            ]
        );
            // dump($genres);
            // die();
        return new JsonResponse($resultat,200,[],true);
    }
}
