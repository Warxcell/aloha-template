<?php

namespace Aloha\Attributes;

use Aloha\Nodes\ElementInterface;

/**
 * @author VM5 Ltd. <office@vm5.bg>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.bg/)
 */
class IdAttribute extends Attribute
{

    /**
     * @var string
     */
    protected $key = 'id';

    /**
     * @var array 
     */
    protected $idToElementMapping = [];

    /**
     * {@inheritdoc}
     */
    public function setElement(ElementInterface $element)
    {
        parent::setElement($element);

        $this->mapElementToId();
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        parent::setValue($value);

        $this->mapElementToId();
    }

    /**
     * @return void
     */
    protected function mapElementToId()
    {
        if ($this->value == null) {
            return;
        }
        if ($this->element == null) {
            return;
        }

        $this->idToElementMapping[$this->value] = $this->element;
    }

    /**
     * Find Element by a given id
     * 
     * @param string $id
     * @return ElementInterface
     */
    public function findElementById($id)
    {

        $mapping = $this->idToElementMapping;

        if (!array_key_exists($id, $mapping)) {
            return;
        }

        return $mapping[$id];
    }

}
