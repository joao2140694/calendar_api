<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User
{

    /**
     * Relaions
     * @ORM\ManyToMany(targetEntity="Slot", inversedBy="users")
     * @ORM\JoinTable(name="users_slots")
     */
    private $slots;


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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_interviewer", type="boolean")
     */
    private $isInterviewer;

    public function __construct() {
        $this->slots = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set isInterviewer
     *
     * @param boolean $isInterviewer
     *
     * @return User
     */
    public function setIsInterviewer($isInterviewer)
    {
        $this->isInterviewer = $isInterviewer;

        return $this;
    }

    /**
     * Get isInterviewer
     *
     * @return bool
     */
    public function isInterviewer()
    {
        return $this->isInterviewer;
    }

    public function toArray()
    {
        $array["id"] = $this->id;
        $array["name"] = $this->name;

        return $array;
        //return get_object_vars($this);
    }

    public function addSlot($slot)
    {
        $this->slots[] = $slot;
    }

    public function removeSlot($slot)
    {
        $this->slots->removeElement($slot);
    }

    public function getSlots()
    {
        return $this->slots->toArray(); // slots é um array collection
    }

    public function hasSlot($slot)
    {
        //element representa um elemento do ArrayCollection slots! 
        $closure = function($key, $element) use ($slot){
            return $element->getId() === $slot->getId();
        };

        return $this->slots->exists($closure);
    }
}

