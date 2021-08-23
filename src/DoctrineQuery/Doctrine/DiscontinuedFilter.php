<?php

namespace DoctrineQuery\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class DiscontinuedFilter extends SQLFilter
{
    // Apply even when JOIN are used
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        // Add Where (x) to DQL query
        if ($targetEntity->getReflectionClass()->name != 'DoctrineQuery\Entity\FortuneCookie') {
            return '';
        } else {
            return sprintf('%s.numberPrinted > %s', $targetTableAlias, $this->getParameter('discontinued'));
        }
    }
}