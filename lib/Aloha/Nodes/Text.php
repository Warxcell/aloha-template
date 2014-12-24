<?php

namespace Aloha\Nodes;

/**
 * @author VM5 Ltd. <office@vm5.bg>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.bg/)
 */
class Text extends AbstractNode implements TextInterface {

    /**
     * @var string
     */
    protected $content;

    /**
     * {@inheritdoc}
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent() {
        return $this->content;
    }
    
    /**
     * {@inheritdoc}
     */
    public function paste() {
        $this->variableResolver->setString($this->content);
        $this->variableResolver->setVariables($this->variables);
        $return = $this->variableResolver->resolve();

        foreach ($this->children as $node) {
            $return .= $node->paste();
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function compile($variableResolverObjectId = null) {

        $output = [];

        if ($variableResolverObjectId == null) {
            $output[] = $this->variableResolver->compile() . PHP_EOL;
            $variableResolverObjectId = $this->variableResolver->getId();
        }

        if ($this->hasChildren()) {
            foreach ($this->children as $child) {
                $output[] = $child->compile($variableResolverObjectId) . PHP_EOL;
            }
        }

        $objectId = $this->getId();
        $output[] = sprintf('$%s = new %s;', $objectId, get_class($this));
        $output[] = sprintf('$%s->setVariableResolver($%s);', $objectId, $variableResolverObjectId);
        $output[] = sprintf('$%s->setContent(\'%s\');', $objectId, $this->getContent()); // !!! content need to be escaped

        if ($this->hasChildren()) {
            foreach ($this->children as $child) {
                $output[] = sprintf('$%s->addChild($%s);', $objectId, $child->getId());
            }
        }

        if (!$this->hasParent()) {
            $output[] = PHP_EOL;
            $output[] = sprintf('return $%s;', $objectId);
        }

        return implode(PHP_EOL, $output);
    }

}
