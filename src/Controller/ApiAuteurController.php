<?php

namespace App\Controller;


use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use App\Repository\NationaliteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiAuteurController extends AbstractController
{
    /**
     * @Route("/api/auteurs", name="api_auteurs", methods={"GET"})
     */
    public function list(AuteurRepository $repo, SerializerInterface $serializer)
    {
        $auteurs   = $repo->findAll();
        $resultat = $serializer->serialize(
            $auteurs,
            'json',
            [
            'groups' =>['listAuteurFull']
            ]
        );
        return new JsonResponse($resultat,200,[],true);
    }

    /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_show", methods={"GET"})
     */
    public function show(Auteur $auteur, SerializerInterface $serializer)
    {
        $resultat = $serializer->serialize(
            $auteur, 
            'json',
            [
            'groups' =>['listAuteurSimple']
            ]
        );
        return new JsonResponse($resultat,Response::HTTP_OK,[],true);
    }

    /**
     * @Route("/api/auteurs", name="api_auteurs_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer,NationaliteRepository $repoNation, EntityManagerInterface $manager, ValidatorInterface $validator, DecoderInterface $decode)
    {
        $data   = $request->getContent();
        $dataTab   = $decode->decode($data, 'json');
        $auteur = new Auteur();
        $nationalite = $repoNation->find($dataTab['nationalité']['id']);
        $serializer->deserialize($data, Auteur::class, "json", ['object_to_populate' => $auteur]);
        $auteur->setNationalité($nationalite);
        
        //gestions des erreurs de validations
        $errors = $validator->validate($auteur);
        if(count($errors))
        {
            $errorsJson = $serializer->serialize($errors, "json");
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        
        $manager->persist($auteur);
        $manager->flush();
       
        return new JsonResponse(
            "Le auteur a bien été créé",
            Response::HTTP_CREATED,[
            "location"=>  $this->generateUrl(
            'api_auteurs_show',
            ["id"=> $auteur->getId()],
             UrlGeneratorInterface::ABSOLUTE_URL)],
            true
        );
    }

     /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_update", methods={"PUT"})
     */
    public function update(Auteur $auteur,Request $request,NationaliteRepository $repoNation, SerializerInterface $serializer, EntityManagerInterface $manager, ValidatorInterface $validator, DecoderInterface $decode)
    {
        $data = $request->getContent();
        $dataTab =  $decode->decode($data, 'json');
        $nationalite = $repoNation->find($dataTab['nationalité']['id']);

        //solution pour l'imbrication d'un objet dans un autre.
        $serializer->deserialize($data, Auteur::class, "json", ['object_to_populate' => $auteur]);
        $auteur->setNationalité($nationalite);

         //gestions des erreurs de validations
         $errors = $validator->validate($auteur);
         if(count($errors))
         {
             $errorsJson = $serializer->serialize($errors, "json");
             return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
         }

        $manager->persist($auteur);
        $manager->flush();

        return new JsonResponse("Le auteur à bien été modifié",Response::HTTP_OK,[],true);
    }

     /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_delete", methods={"DELETE"})
     */
    public function delete(Auteur $auteur, EntityManagerInterface $manager)
    {
        
        $manager->remove($auteur);
        $manager->flush();

        return new JsonResponse("L'auteur à bien été supprimé",Response::HTTP_OK,[],false);
    }
}
