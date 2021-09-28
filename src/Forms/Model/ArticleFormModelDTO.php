<?php

namespace Forms\Model;

use Forms\Validator\MyUniqUser; //
// use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UniqueEntity(
 *     DONT WORKS ON NON-ENTITY ! use CustomConstraint
 * )
 */
class ArticleFormModelDTO
{
    /**
     * @Assert\NotBlank(message="Get creative and think of a title (DTO)!")
     * 
     * @\Forms\Validator\MyUniqUser()
     */
    public $title;
    public $content;
    public $unexistedParam;

    /**
     * @Assert\NotNull(message="Please set an author - DTO")
     */
    public $author;
    public $agreeTerms;
    public $publishedAt;
}
