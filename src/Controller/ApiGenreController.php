<?php

namespace App\Controller;


use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="api_genres", methods={"GET"})
     */
    public function list(GenreRepository $repo, SerializerInterface $serializer)
    {
        $genres   = $repo->findAll();
        $resultat = $serializer->serialize(
            $genres,
            'json',
            [
            'groups' =>['listGenreFull']
            ]
        );
        return new JsonResponse($resultat,200,[],true);
    }

    /**
     * @Route("/api/genres/{id}", name="api_genres_show", methods={"GET"})
     */
    public function show(Genre $genre, SerializerInterface $serializer)
    {
        $resultat = $serializer->serialize(
            $genre, 
            'json',
            [
            'groups' =>['listGenreSimple']
            ]
        );
        return new JsonResponse($resultat,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/genres", name="api_genres_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        $data   = $request->getContent();
        $genre  = $serializer->deserialize($data, Genre::class, 'json');
        
        //gestions des erreurs de validations
        $errors = $validator->validate($genre);
        if(count($errors))
        {
            $errorsJson = $serializer->serialize($errors, "json");
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        
        $manager->persist($genre);
        $manager->flush();
       
        return new JsonResponse(
            "Le genre a bien été créé",
            Response::HTTP_CREATED,[
            "location"=>  $this->generateUrl(
            'api_genres_show',
            ["id"=> $genre->getId()],
             UrlGeneratorInterface::ABSOLUTE_URL)],
            true
        );
    }

     /**
     * @Route("/api/genres/{id}", name="api_genres_update", methods={"PUT"})
     */
    public function update(Genre $genre,Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        $data = $request->getContent();
        $resultat = $serializer->deserialize($data, Genre::class, "json", ['object_to_populate' => $genre]);

         //gestions des erreurs de validations
         $errors = $validator->validate($genre);
         if(count($errors))
         {
             $errorsJson = $serializer->serialize($errors, "json");
             return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
         }

        $manager->persist($genre);
        $manager->flush();

        return new JsonResponse("Le genre à bien été modifié",Response::HTTP_OK,[],true);
    }

     /**
     * @Route("/api/genres/{id}", name="api_genres_delete", methods={"DELETE"})
     */
    public function delete(Genre $genre, EntityManagerInterface $manager)
    {
        
        $manager->remove($genre);
        $manager->flush();

        return new JsonResponse("Le genre à bien été supprimé",Response::HTTP_OK,[],false);
    }
}
