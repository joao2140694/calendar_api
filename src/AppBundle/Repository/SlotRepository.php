<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Slot;

/**
 * SlotRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SlotRepository extends \Doctrine\ORM\EntityRepository
{
	public function add($parameters, $user)
	{

		//expected date  - "dd/mm/yyyy"
		$date = \DateTime::createFromFormat('d/m/Y', $parameters['date']);
		$hour = $parameters['hour'];

		$criteria = array(
			'date' => $date,
			'hour' => $hour,
			);
		$slot = $this->findOneBy($criteria);

		if(!$slot) 
		{
			$slot = $this->create($date, $hour);
		}
		else {
			var_dump("ja existe dito slot, vai ser reaproveitado.");
			//var_dump($slot);
		}

		$slot->addUser($user);
		$this->getEntityManager()->flush();
    	//associar slot a utilizador

		//TODO - throw qualquer coisa se isto não correr bem
	}

	private function create($date, $hour)
	{
		$slot = new Slot();
    	$slot->setHour($hour);
    	$slot->setDate($date);

		$this->getEntityManager()->persist($slot);
		$this->getEntityManager()->flush();

		return $slot;
	}

	public function remove($user, $slot_id)
	{
		$slot = $this->find($slot_id);

		if(!$slot)
		{
			//fazer throw se o slot noa exitir
		}
		
		$slot->removeUser($user);

		/*if($slot->getUsers->isEmpty())
		{

		}*/
		$this->getEntityManager()->flush();
	}
}
