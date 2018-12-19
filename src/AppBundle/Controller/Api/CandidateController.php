<?php

namespace AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Patch;

use AppBundle\Entity\User;

class CandidateController extends FOSRestController
{	

    /**
     * @Get("/candidate/{id}")
     */
    public function getAction($id)
    {
        $userRepository = $this->getDoctrine()->getRepository("AppBundle:User");
        $candidate = $userRepository->getCandidate($id);

        if(!$candidate)
        {
            return new JsonResponse("Not Found");
        }
        
        return new JsonResponse($candidate->toArray());
        
    }

    /**
     * @Get("/candidates")
     */
    public function getAllAction(Request $request)
    {
        $userRepository = $this->getDoctrine()->getRepository("AppBundle:User");
        $candidates = $userRepository->getAllCandidates();

        //de certeza que deve haver um maneira mais eficiente de fazer isto...
        $array = [];
        foreach($candidates as $candidate) 
        {
            array_push($array, $candidate->toArray());
        }

        $aux['candidates'] = $array;
        return new JsonResponse($aux);
    }

    
    /**
     * @Post("/candidate")
     */
    public function createAction(Request $request)
    {
        //TODO - VALIDAR!
    	$userRepository = $this->getDoctrine()->getRepository("AppBundle:User");

    	$userRepository->createCandidate($request->request->get("name"));
    	$mgs = "Se chegou aqui penso que esta tudo bem!";

        return new JsonResponse($mgs);
    }

    /**
     * @Delete("/candidate/{id}")
     */
    public function deleteAction(User $user)
    {
        //TODO - validar input

        if($user->isInterviewer()) //trocar isto po ctach de excepcao!
        {
            return new JsonResponse("Error - id invalido");
        }

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($user);
        $entityManager->flush();

        //TODO - VALIDAR SE FOI APAGADO COM SUCESSO!

        return new JsonResponse("OK Deleted!!");
    }

}
