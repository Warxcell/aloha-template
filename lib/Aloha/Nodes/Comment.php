<?php

namespace Aloha\Nodes;

/**
 * @author VM5 Ltd. <office@vm5.eu>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.eu/)
 */
class Comment extends AbstractNode implements CommentInterface
{

    /**
     * @var string
     */
    protected $content;

    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * {@inheritdoc}
     */
    public function paste()
    {
        return sprintf(self::COMMENT_PATTERN, $this->content);
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
        $output[] = sprintf('$%s = new %s()', $objectId, get_class($this));
        $output[] = sprintf('   ->setVariableResolver($%s)', $variableResolverObjectId);
        $output[] = sprintf('   ->setContent(\'%s\')', $this->getContent()); // !!! content need to be escaped
        $output[] = ';';

        return implode(PHP_EOL, $output);
    }

}
