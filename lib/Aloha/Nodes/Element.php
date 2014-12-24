<?php

namespace Aloha\Nodes;

use Aloha\Attributes\AttributeInterface;

/**
 * @author VM5 Ltd. <office@vm5.bg>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.bg/)
 */
class Element extends AbstractNode implements ElementInterface
{

    /**
     * @var string 
     */
    protected $tag;

    /**
     * @var AttributeInterface[]
     */
    protected $attributes = [];

    /**
     * @var bool
     */
    protected $isSelfClosing;

    /**
     * @var bool
     */
    protected $isVoid;

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $key
     */
    public function hasAttribute($key)
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @param string $key
     */
    public function getAttribute($key)
    {
        if ($this->hasAttribute($key)) {
            return $this->attributes[$key];
        }
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setAttribute(AttributeInterface $attribute)
    {
        $this->attributes[$attribute->getKey()] = $attribute;
    }

    /**
     * @param string $key
     */
    public function removeAttribute($key)
    {
        unset($this->attributes[$key]);
    }

    /**
     * @return string
     */
    protected function pasteAttributes()
    {
        if (!$this->attributes) {
            return '';
        }
        $attributes = [];
        foreach ($this->attributes as $attribute) {
            $attributes[] = $attribute->paste();
        }
        return ' ' . implode(' ', $attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * {@inheritdoc}
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function getIsSelfClosing()
    {
        return $this->isSelfClosing;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsSelfClosing($isSelfClosing)
    {
        $this->isSelfClosing = $isSelfClosing;
    }

    /**
     * {@inheritdoc}
     */
    public function getIsVoid()
    {
        return $this->isVoid;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsVoid($isVoid)
    {
        $this->isVoid = $isVoid;
    }

    /**
     * {@inheritdoc}
     */
    public function paste()
    {
        if ($this->isSelfClosing) {
            $return = sprintf(self::TAG_SELF_CLOSING, $this->tag, $this->pasteAttributes());
        } elseif ($this->isVoid) {
            $return = sprintf(self::TAG_VOID, $this->tag, $this->pasteAttributes());
        } else {
            $return = sprintf(self::TAG_OPEN, $this->tag, $this->pasteAttributes());
            foreach ($this->children as $node) {
                $return .= $node->paste();
            }
            $return .= sprintf(self::TAG_CLOSE, $this->tag);
        }

        return $return;
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

        if ($this->hasChildren()) {
            foreach ($this->children as $child) {
                $output[] = $child->compile($variableResolverObjectId) . PHP_EOL;
            }
        }

        $objectId = $this->getId();
        $output[] = sprintf('$%s = new %s;', $objectId, get_class($this));
        $output[] = sprintf('$%s->setVariableResolver($%s);', $objectId, $variableResolverObjectId);
        $output[] = sprintf('$%s->setTag(\'%s\');', $objectId, $this->tag);

        $isSelfClosing = 'false';
        if ($this->isSelfClosing) {
            $isSelfClosing = 'true';
        }
        $output[] = sprintf('$%s->setIsSelfClosing(%s);', $objectId, $isSelfClosing);

        $isVoid = 'false';
        if ($this->isVoid) {
            $isVoid = 'true';
        }

        $output[] = sprintf('$%s->setIsVoid(%s);', $objectId, $isVoid);

        if ($this->attributes != null) {
            foreach ($this->attributes as $attribute) {
                $output[] = PHP_EOL;
                $output[] = $attribute->compile($variableResolverObjectId) . PHP_EOL;
                $output[] = sprintf('$%s->setAttribute($%s);', $objectId, $attribute->getId());
            }
        }

        if ($this->hasChildren()) {
            foreach ($this->children as $child) {
                $output[] = sprintf('$%s->addChild($%s);', $objectId, $child->getId());
            }
        }

        if (!$this->hasParent()) { // if the element is the root
            $output[] = PHP_EOL;
            $output[] = sprintf('return $%s;', $objectId);
        }

        return implode(PHP_EOL, $output);
    }

}
