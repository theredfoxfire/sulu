<?php

/*
 * This file is part of the Sulu CMS.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Component\Rest\ListBuilder\Expression\Doctrine;

use Doctrine\ORM\QueryBuilder;
use Sulu\Component\Rest\ListBuilder\Doctrine\FieldDescriptor\AbstractDoctrineFieldDescriptor;
use Sulu\Component\Rest\ListBuilder\Expression\WhereExpressionInterface;
use Sulu\Component\Rest\ListBuilder\ListBuilderInterface;

/**
 * Represents a WHERE expression for doctrine - needs a field, a value and a comparator
 */
class DoctrineWhereExpression extends AbstractDoctrineExpression implements WhereExpressionInterface
{
    /**
     * Field descriptor used for comparison
     *
     * @var $fieldName AbstractDoctrineFieldDescriptor
     */
    protected $field;

    /**
     * Value which is used to compare
     *
     * @var $value
     */
    protected $value;

    /**
     * Comparator to compare values
     *
     * @var $comparator AbstractDoctrineFieldDescriptor
     */
    protected $comparator;

    function __construct(AbstractDoctrineFieldDescriptor $field, $value, $comparator = ListbuilderInterface::WHERE_COMPARATOR_EQUAL)
    {
        $this->field = $field;
        $this->value = $value;
        $this->comparator = $comparator;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatement(QueryBuilder $queryBuilder)
    {
        $paramName = $this->getFieldName() . uniqid(true);

        if ($this->getValue() === null) {
            return $this->field->getSelect() . ' ' . $this->convertNullComparator($this->getComparator());
        } elseif ($this->getComparator() === 'LIKE') {
            $queryBuilder->setParameter($paramName, '%' . $this->getValue() . '%');
        } else {
            $queryBuilder->setParameter($paramName, $this->getValue());
        }

        return $this->field->getSelect() . ' ' . $this->getComparator() . ' :' . $paramName;
    }

    /**
     * @param $comparator
     *
     * @return string
     */
    protected function convertNullComparator($comparator)
    {
        switch ($comparator) {
            case ListBuilderInterface::WHERE_COMPARATOR_EQUAL:
                return 'IS NULL';
            case ListBuilderInterface::WHERE_COMPARATOR_UNEQUAL:
                return 'IS NOT NULL';
            default:
                return $comparator;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getComparator()
    {
        return $this->comparator;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldName()
    {
        return $this->field->getName();
    }
}

