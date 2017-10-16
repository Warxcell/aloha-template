<?php

namespace Aloha\Nodes;

/**
 * @author VM5 Ltd. <office@vm5.bg>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.bg/)
 */
class Doctype extends AbstractNode implements DoctypeInterface
{

    /**
     * @var string 
     */
    protected $rootElementName;

    /**
     * @var string 
     */
    protected $idType;

    /**
     * @var string 
     */
    protected $publicId;

    /**
     * @var type 
     */
    protected $systemId;

    public function setIdType($type)
    {
        $this->idType = $type;

        return $this;
    }

    public function setPublicId($id)
    {
        $this->publicId = $id;

        return $this;
    }

    public function setRootElementName($name)
    {
        $this->rootElementName = $name;

        return $this;
    }

    public function setSystemId($id)
    {
        $this->systemId = $id;

        return $this;
    }

    public function paste()
    {
        $idType = '';
        if ($this->idType) {
            $idType = ' ' . $this->idType;
        }

        $publicId = '';
        if ($this->publicId) {
            $publicId = ' "' . $this->publicId . '"';
        }

        $systemId = '';
        if ($this->systemId) {
            $systemId = ' "' . $systemId . '"';
        }

        return sprintf(self::DOCTYPE_PATTERN, $this->rootElementName, $idType, $publicId, $systemId);
    }

    /**
     * @param NodeInterface $node
     * @throws Exception
     */
    public function addChild(NodeInterface $node)
    {
        throw new \Exception('Cannot add child to Doctype node!');
    }

    /**
     * @throws Exception
     */
    public function getChildren()
    {
        throw new \Exception('Doctype node has no child elements');
    }

    /**
     * @return boolean
     */
    public function hasChildren()
    {
        return false;
    }

    /**
     * @param string $variableResolverObjectId
     * @return string
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
        $output[] = sprintf('$%s->setVariableResolver($%s);', $objectId, $variableResolverObjectId);
        $output[] = sprintf('$%s->setRootElementName(\'%s\');', $objectId, $this->rootElementName);

        if ($this->idType !== null) {
            $output[] = sprintf('$%s->setIdType(\'%s\');', $objectId, $this->idType);
        }
        if ($this->publicId !== null) {
            $output[] = sprintf('$%s->setPublicId(\'%s\');', $objectId, $this->publicId);
        }
        if ($this->systemId !== null) {
            $output[] = sprintf('$%s->setSystemId(\'%s\');', $objectId, $this->systemId);
        }

        return implode(PHP_EOL, $output);
    }

}
