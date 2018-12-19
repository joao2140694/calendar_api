<?php

namespace AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;

use AppBundle\Entity\Slot;
use AppBundle\Entity\User;

class SlotController extends FOSRestController
{	
	 /**
     * @Get("/slots/search/interviews_for_candidate/{id}")
     */
	public function searchAction(Request $request, $id)
 	{
 		//TODO VALIDAR INPUT.
	 	//tem de receber um ou ou varios ids de intervistadores!

	 	$userRepository = $this->getDoctrine()->getRepository("AppBundle:User");
    	$candidate = $userRepository->getCandidate($id); 

    	if(!$candidate) //trocar isto po ctach de excepcao!
    	{
    		return new JsonResponse("Error - id invalido");
    	}

    	$candidateSlots = $candidate->getSlots(); //array de objectos

		$interviewersId = $request->query->get('interviewers');
		if(!$interviewersId)
		{
			return new JsonResponse("Error - no interviewers listed");
		}

		$slotsMatched = [];
		foreach ($interviewersId as  $id)
		{
			$interviewer = $userRepository->getInterviewer($id); 

	    	if(!$candidate) //trocar isto por catch de excepcao!
	    	{
	    		continue;
	    	}

	    	foreach ($candidateSlots as $CandidateSlot) {
				if ($interviewer->hasSlot($CandidateSlot))
				{
					array_push($slotsMatched, $CandidateSlot->toArray());
				}
	    	}
		}

		$aux["slots"] = $slotsMatched;
		return new JsonResponse($aux);
	}

    /**
     * @Get("/slots/candidate/{id}")
     */
    public function getAllOfCandidateAction($id) 
    {
    	//TODO VALIDAR INPUT.

	 	$userRepository = $this->getDoctrine()->getRepository("AppBundle:User");
    	$candidate = $userRepository->getCandidate($id); 

    	if(!$candidate) //trocar isto po ctach de excepcao!
    	{
    		return new JsonResponse("Error - id invalido");
    	}

		return new JsonResponse($this->getAll($candidate));
    }

    /**
     * @Get("/slots/interviewer/{id}")
     */
    public function getAllOfInterviewerAction($id) 
    {
        //TODO - VALIDAR!

	 	$userRepository = $this->getDoctrine()->getRepository("AppBundle:User");
    	$interviewer = $userRepository->getInterviewer($id); 

    	if(!$interviewer) //trocar isto po ctach de excepcao!
    	{
    		return new JsonResponse("Error - id invalido");
    	}

		return new JsonResponse($this->getAll($interviewer));
    }

    private function getAll($user)
    {
    	$userSlots = $user->getSlots();
		
		$slots = [];
		foreach($userSlots as $slot)
		{
			//var_dump($slot->toArray());
			array_push($slots, $slot->toArray());
		}

		$aux["slots"] = $slots;

    	return $aux;
    }
	/**
     * @Post("/slot/candidate/{id}")
     */
    public function createForCandidateAction(Request $request, $id)
    {
    	//TODO - Isto tem de ser feito para aceitar uma coleção de slots

		$userRepository = $this->getDoctrine()->getRepository("AppBundle:User");
    	$candidate = $userRepository->getCandidate($id); 

    	if(!$candidate) //trocar isto po ctach de excepcao!
    	{
    		return new JsonResponse("Error - id invalido");
    	}

		return $this->createSlot($request, $candidate);

        //TODO - catch o que correr mal
    }

	/**
     * @Post("/slot/interviewer/{id}")
     */
    public function createForInterviewerAction(Request $request, $id)
    {
    	//TODO - Isto tem de ser feito para aceitar uma coleção de slots

		$userRepository = $this->getDoctrine()->getRepository("AppBundle:User");
    	$interviewer = $userRepository->getInterviewer($id); 

    	if(!$interviewer) //trocar isto po ctach de excepcao!
    	{
    		return new JsonResponse("Error - id invalido");
    	}

    	return $this->createSlot($request, $interviewer);

        //TODO - catch o que correr mal
    }

    private function createSlot($request, $user)
    {
    	//TODO - VALIDAR INPUT
    	$parameters = $request->request->all();

    	$slotRepository = $this->getDoctrine()->getRepository("AppBundle:Slot");
    	$slotRepository->add($parameters, $user);	

        return new JsonResponse("OK - slot created");
    }

	/**
     * @Delete("/slot/{slot_id}/candidate/{candidate_id}")
     */
    public function deleteOfCandidateAction($slot_id, $candidate_id)
    {
		$userRepository = $this->getDoctrine()->getRepository("AppBundle:User");
    	$candidate = $userRepository->getCandidate($candidate_id); 

    	if(!$candidate) //trocar isto po ctach de excepcao!
    	{
    		return new JsonResponse("Error - id invalido");
    	}

		$slotRepository = $this->getDoctrine()->getRepository("AppBundle:Slot");
 		$slotRepository->remove($candidate, $slot_id);

 		//TODO - CATCH de erros do repositorio!

        return new JsonResponse("OK - slot deleted");
    }

    /**
     * @Delete("/slot/{slot_id}/interviewer/{interviewer_id}")
     */
    public function deleteOfInterviewerAction($slot_id, $interviewer_id)
    {
		$userRepository = $this->getDoctrine()->getRepository("AppBundle:User");
    	$interviewer = $userRepository->getInterviewer($interviewer_id); 

    	if(!$interviewer) //trocar isto po ctach de excepcao!
    	{
    		return new JsonResponse("Error - id invalido");
    	}

		$slotRepository = $this->getDoctrine()->getRepository("AppBundle:Slot");
 		$slotRepository->remove($interviewer, $slot_id);

 		//TODO - CATCH de erros do repositorio!

        return new JsonResponse("OK - slot deleted");
    }
}