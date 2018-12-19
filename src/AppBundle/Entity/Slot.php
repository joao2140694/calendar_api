<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slot
 *
 * @ORM\Table(name="slots")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SlotRepository")
 */
class Slot
{
    /**
     * Relaions
     * @ORM\ManyToMany(targetEntity="User", mappedBy="slots")
     *      
     */
    private $users;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="hour", type="decimal", precision=2, scale=0)
     */
    private $hour;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;


    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set hour
     *
     * @param string $hour
     *
     * @return slot
     */
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * Get hour
     *
     * @return string
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return slot
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    public function addUser($user)
    {
        $closure = function($key, $element) use ($user){
            return $element->getId() === $user->getId();
        };

        if(!$this->users->exists($closure))
        {
            $this->users[] = $user;
            $user->addSlot($this);
        }      
    }

    public function removeUser($user)
    {
        $this->users->removeElement($user);
        $user->removeSlot($this);
    }

    public function toArray()
    {
        $array["id"] = $this->id;
        $array["hour"] = $this->hour;
        $array["date"] = $this->date->format('d/m/Y');

        return $array;
        //return get_object_vars($this);
    }
}

