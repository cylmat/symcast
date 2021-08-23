<?php

namespace DoctrineQuery\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *  @ORM\Entity(repositoryClass="DoctrineQuery\Repository\FortuneRepository")
 */
class FortuneCookie
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
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="fortuneCookies", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="fortune", type="string", length=255)
     */
    private $fortune;

    /**
     * @var integer
     *
     * @ORM\Column(name="numberPrinted", type="integer")
     */
    private $numberPrinted = 1;

    /**
     * Get the value of id
     *
     * @return integer
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of category
     *
     * @return  Category
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @param  Category  $category
     *
     * @return  self
     */ 
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of fortune
     *
     * @return  string
     */ 
    public function getFortune()
    {
        return $this->fortune;
    }

    /**
     * Set the value of fortune
     *
     * @param  string  $fortune
     *
     * @return  self
     */ 
    public function setFortune(string $fortune)
    {
        $this->fortune = $fortune;

        return $this;
    }

    /**
     * Get the value of numberPrinted
     *
     * @return  integer
     */ 
    public function getNumberPrinted()
    {
        return $this->numberPrinted;
    }

    /**
     * Set the value of numberPrinted
     *
     * @param  integer  $numberPrinted
     *
     * @return  self
     */ 
    public function setNumberPrinted($numberPrinted)
    {
        $this->numberPrinted = $numberPrinted;

        return $this;
    }
}
