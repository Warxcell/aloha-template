<?php

namespace Aloha\Nodes;

use Aloha\VariableResolvers\VariableResolverInterface;

/**
 * @author VM5 Ltd. <office@vm5.bg>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.bg/)
 */
abstract class AbstractNode implements NodeInterface
{

    /**
     * Reference to the root node of this tree
     * 
     * @var NodeInterface
     */
    protected $root;

    /**
     * @var NodeInterface
     */
    protected $parent;

    /**
     * @var \SplObjectStorage
     */
    protected $children;

    /**
     * @var mixed[]
     */
    protected $variables = [];

    /**
     * @var VariableResolverInterface 
     */
    protected $variableResolver;

    /**
     * 
     */
    public function __construct()
    {
        $this->children = new \SplObjectStorage();
    }

    /**
     * Generate Node unique ID based on spl_object_hash()
     * 
     * @return string
     */
    public function getId()
    {
        return 'node' . spl_object_hash($this);
    }

    /**
     * @param VariableResolverInterface $resolver
     */
    public function setVariableResolver(VariableResolverInterface $resolver)
    {
        $this->variableResolver = $resolver;
        return $this;
    }

    /**
     * @param string $variable
     * @param mixed $value
     */
    public function setVariable($variable, $value)
    {
        $this->variables[$variable] = $value;

        foreach ($this->children as $child) {
            $child->setVariable($variable, $value);
        }

        return $this;
    }

    /**
     * @return NodeInterface
     */
    function getRoot()
    {
        if (!$this->hasParent()) {
            $this->root = $this;
        }
        return $this->root;
    }

    /**
     * @param NodeInterface $root
     */
    function setRoot(NodeInterface $root)
    {
        $this->root = $root;
        foreach ($this->children as $child) {
            $child->setRoot($root);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return ($this->children->count() > 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(NodeInterface $node)
    {
        $this->children->attach($node);

        $node->setRoot($this->getRoot());
        $node->setParent($this);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(NodeInterface $node)
    {
        $this->parent = $node;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return ($this->parent != null);
    }

    /**
     * Compile Node to PHP valid code
     * The compiled code can be used for file caching
     * 
     * @abstract
     * @return string
     */
    abstract public function compile();
}
