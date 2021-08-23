<?php

namespace DoctrineQuery\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * Category
 *
 * @ORM\Entity(repositoryClass="DoctrineQuery\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var integer
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
     * @var string
     *
     * @ORM\Column(name="iconKey", type="string", length=20)
     */
    private $iconKey;

    /**
     * 
     * @var object
     * @ORM\OneToMany(targetEntity="FortuneCookie", mappedBy="category", cascade={"persist"}))
     */
    private $fortuneCookies;

    /**
     * Get the value of id
     *
     * @return  integer
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  integer  $id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of iconKey
     *
     * @return  string
     */ 
    public function getIconKey()
    {
        return $this->iconKey;
    }

    /**
     * Set the value of iconKey
     *
     * @param  string  $iconKey
     *
     * @return  self
     */ 
    public function setIconKey(string $iconKey)
    {
        $this->iconKey = $iconKey;

        return $this;
    }

    /**
     * Get the value of fortuneCookies
     *
     */ 
    public function getFortuneCookies(): PersistentCollection
    {
        return $this->fortuneCookies;
    }

    /**
     * Set the value of fortuneCookies
     *
     * @return  self
     */ 
    public function setFortuneCookies( $fortuneCookies)
    {
        $this->fortuneCookies = $fortuneCookies;

        return $this;
    }
}
