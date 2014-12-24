<?php

namespace Aloha\Attributes;

use Aloha\Nodes\ElementInterface;
use Aloha\VariableResolvers\VariableResolverInterface;

/**
 * @author VM5 Ltd. <office@vm5.bg>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.bg/)
 */
class Attribute implements AttributeInterface
{

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $value;

    /**
     *
     * @var Element
     */
    protected $element;

    /**
     * @var VariableResolverInterface 
     */
    protected $variableResolver;

    /**
     * @return string
     */
    public function getId()
    {
        return 'attribute' . spl_object_hash($this);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->paste();
    }

    /**
     * {@inheritdoc}
     */
    public function setElement(ElementInterface $element)
    {
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @param VariableResolverInterface $resolver
     */
    public function setVariableResolver(VariableResolverInterface $resolver)
    {
        $this->variableResolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return $this->key;
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
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function paste()
    {
        if ($this->value === null) {
            $attribute = sprintf(self::ATTRIBUTE_EMPTY_PATTERN, $this->key);
        } else {
            $attribute = sprintf(self::ATTRIBUTE_PATTERN, $this->key, $this->value);
        }
        return $attribute;
    }

    /**
     * {@inheritdoc}
     */
    public function compile($variableResolverObjectId = null)
    {
        $output = [];

        if ($variableResolverObjectId == null) {
            $output[]                 = $this->variableResolver->compile() . PHP_EOL;
            $variableResolverObjectId = $this->variableResolver->getId();
        }

        $objectId = $this->getId();
        $output[] = sprintf('$%s = new %s;', $objectId, get_class($this));
        $output[] = sprintf('$%s->setElement($%s);', $objectId, $this->element->getId());
        $output[] = sprintf('$%s->setVariableResolver($%s);', $objectId, $variableResolverObjectId);
        $output[] = sprintf('$%s->setKey(\'%s\');', $objectId, $this->key);
        if ($this->value !== null) {
            $output[] = sprintf('$%s->setValue(\'%s\');', $objectId, $this->value);
        }

        return implode(PHP_EOL, $output);
    }

}
