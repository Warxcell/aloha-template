<?php

namespace Aloha\Attributes;

use Aloha\Nodes\ElementInterface;
use Aloha\VariableResolvers\VariableResolverInterface;

/**
 * @author VM5 Ltd. <office@vm5.bg>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.bg/)
 */
interface AttributeInterface {

    const ATTRIBUTE_PATTERN = '%s="%s"';
    const ATTRIBUTE_EMPTY_PATTERN = '%s';

    /**
     * Set the element on which this attribute belongs to
     * 
     * @param ElementInterface $element
     */
    public function setElement(ElementInterface $element);

    /**
     * @param VariableResolverInterface $resolver
     */
    public function setVariableResolver(VariableResolverInterface $resolver);

    /**
     * @return ElementInterface
     */
    public function getElement();

    /**
     * @param sring $key
     */
    public function setKey($key);

    /**
     * @return sring
     */
    public function getKey();

    /**
     * @param string $value
     */
    public function setValue($value);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @return string
     */
    public function paste();
}
