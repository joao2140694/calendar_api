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

class InterviewerController extends FOSRestController
{	
    /**
     * @Get("/interviewer/{id}")
     */
    public function getAction($id) //doctrine manda 404 se noa encontrar!
    {
    	$userRepository = $this->getDoctrine()->getRepository("AppBundle:User");
        $interviewer = $userRepository->getInterviewer($id);

        if($interviewer)
        {
            return new JsonResponse($interviewer->toArray());
        }
        
        return new JsonResponse("Not Found");
    }

    /**
     * @Get("/interviewers")
     */
    public function getAllAction(Request $request)
    {
        $userRepository = $this->getDoctrine()->getRepository("AppBundle:User");
        $interviewers = $userRepository->getAllInterviewers();


        //de certeza que deve haver um maneira mais eficiente de fazer isto...
        $array = [];
        foreach($interviewers as $interviewer) 
        {
            array_push($array, $interviewer->toArray());
        }

        $aux['interviewers'] = $array;
        return new JsonResponse($aux);
    }

    /**
     * @Post("/interviewer")
     */
    public function createAction(Request $request)
    {
        //TODO - VALIDAR REQUEST!
        $userRepository = $this->getDoctrine()->getRepository("AppBundle:User");

        $userRepository->createInterviewer($request->request->get("name"));

        //TODO - VALIDAR SE FOI INSERIDO COM SUCESSO!
        return new JsonResponse("OK Created!");
    }


    /**
     * @Delete("/interviewer/{id}")
     */
    public function deleteAction(User $user)
    {
        //TODO - validar input

        if(!$user->isInterviewer()) //trocar isto po ctach de excepcao!
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
