<?php

namespace SecurityAuth\Entity;

use Doctrine\DBAL\Schema\Table;
use Doctrine\ORM\Mapping as ORM;
use SecurityAuth\Entity\User;
use SecurityAuth\Repository\ApiTokenRepository;
use SecurityAuth\Repository\ArticleRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 *
 * @UniqueEntity(
 *  fields={"title"},
 *  message="I think this title already exists!"
 * )
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotNull(message="Please set an author")
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * 
     * @Assert\NotBlank(message="Get creative and think of a title!")
     */
    private $title;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="date")
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="date")
     */
    private $agreeTermsAt;


    // tuto: dependents fields
    public $location = null;
    public $specificLocationName = null;

    public function notused_onlyinfo_setLocation(?string $location): self
    {
        if (!$this->location || $this->location === 'interstellar_space') {
            //$this->setSpecificLocationName(null);
        }
    }
    // -tuto

    public function __toString()
    {
        return $this->author;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (false !== stripos($this->getTitle(), 'borg')) {
            $context->buildViolation('Um.. the Bork kinda makes us nervous')
                ->atPath('title')
                ->addViolation();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content = null): self
    {
        $this->content = $content;

        return $this;
    }

    public function getTitle(): ?string // @todo understand why null bug on submit
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function isPublished(): bool
    {
        return $this->publishedAt !== null;
    }

    public function getPublishedAt(): \DateTime
    {
        return $this->publishedAt ?? new \DateTime();
    }

    public function setPublishedAt(\DateTime $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /*public function getAgreeTermsAt(): \DateTime
    {
        return $this->agreeTermsAt;
    }

    public function setAgreeTermsAt(\DateTime $agreeTermsAt): self
    {
        $this->agreeTermsAt = $agreeTermsAt;

        return $this;
    }*/

    // cleaner way
    public function agreeTerms()
    {
        $this->agreeTermsAt = new \DateTime();
    }
}