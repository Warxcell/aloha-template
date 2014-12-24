<?php

namespace Aloha\Nodes;

use Aloha\VariableResolvers\VariableResolverInterface;

/**
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.bg/)
 */
interface NodeInterface {

    /**
     * @return string
     */
    public function getId();

    /**
     * @param VariableResolverInterface $resolver
     */
    public function setVariableResolver(VariableResolverInterface $resolver);

    /**
     * @param NodeInterface $root
     */
    public function setRoot(NodeInterface $root);

    /**
     * @return NodeInterface
     */
    public function getRoot();

    /**
     * @param NodeInterface $node
     */
    public function addChild(NodeInterface $node);

    /**
     * @return \SplObjectStorage
     */
    public function getChildren();

    /**
     * @param NodeInterface $node
     */
    public function setParent(NodeInterface $node);

    /**
     * @return NodeInterface
     */
    public function getParent();

    /**
     * @param string $variable
     * @param mixed $value
     */
    public function setVariable($variable, $value);

    /**
     * @return string
     */
    public function paste();
}
