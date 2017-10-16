<?php

namespace Aloha;

use Aloha\Attributes\AttributeInterface;
use Aloha\Nodes\NodeInterface;
use Aloha\Nodes\ElementInterface;
use Aloha\Nodes\TextInterface;
use Aloha\Parsers\ParserInterface;
use Aloha\Parsers\HTML5Parser;
use Aloha\VariableResolvers\VariableResolverInterface;
use Aloha\VariableResolvers\RegexResolver;

/**
 * Aloha XML /(X)HTML template engine
 * 
 * @author Borislav Lesichkov <lesichkov@gmail.com>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @author VM5 Ltd. <office@vm5.eu>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.eu/)
 */
class Template
{

    // nodes
    const NODE_DOCTYPE   = 'doctype';
    const NODE_ELEMENT   = 'element';
    const NODE_COMMENT   = 'comment';
    const NODE_TEXT      = 'text';
    const NODE_ATTRIBUTE = 'attribute';

    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * @var VariableResolverInterface
     */
    protected $variableResolver;

    /**
     * @var string[]
     */
    protected $nodeMapping = [
        self::NODE_DOCTYPE   => '\Aloha\Nodes\Doctype',
        self::NODE_ELEMENT   => '\Aloha\Nodes\Element',
        self::NODE_COMMENT   => '\Aloha\Nodes\Comment',
        self::NODE_TEXT      => '\Aloha\Nodes\Text',
        self::NODE_ATTRIBUTE => '\Aloha\Attributes\Attribute'
    ];

    /**
     * @var string[]
     */
    protected $tagMapping = [];

    /**
     * @var string[] 
     */
    protected $attributeMapping = [
        'id' => '\Aloha\Attributes\IdAttribute'
    ];

    /**
     * 
     */
    public function __construct()
    {
        $this->setParser(new HTML5Parser());
        $this->setVariableResolver(new RegexResolver());
    }

    /**
     * @param ParserInterface $parser
     * @return void
     */
    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;
        $this->parser->setTemplate($this);
    }

    /**
     * @param VariableResolverInterface $resolver
     */
    public function setVariableResolver($resolver)
    {
        $this->variableResolver = $resolver;
    }

    /**
     * @return VariableResolverInterface
     */
    public function getVariableResolver()
    {
        return $this->variableResolver;
    }

    /**
     * @param string $type
     * @param string $class
     * @throws \Exception
     */
    public function overrideNodeMapping($type, $class)
    {
        if (!array_key_exists($type, $this->nodeMapping)) {
            throw new \Exception(sprintf('Node type "%s" does not exist!', $type));
        }

        $this->nodeMapping[$type] = $class;
    }

    /**
     * @param string $class
     * @return NodeInterface;
     */
    protected function instanceNode($class)
    {
        /* @var $node NodeInterface */
        $node = new $class;
        $node->setVariableResolver($this->variableResolver);
        return $node;
    }

    /**
     * @param string $type
     * @return NodeInterface
     */
    public function instanceNodeByType($type)
    {
        return $this->instanceNode($this->nodeMapping[$type]);
    }

    /**
     * @param string $tag
     * @param string $class
     * @throws \Exception
     * @return void
     */
    public function addTagMapping($tag, $class)
    {
        if (array_key_exists($tag, $this->tagMapping)) {
            throw new \Exception(sprintf('Tag "%s" is already mapped to class "%s". 
            You can override it by using overrideTagMapping() method.', $tag, $this->tagMapping[$tag]));
        }
        $this->tagMapping[$tag] = $class;
    }

    /**
     * @param string $tag
     * @param string $class
     * @throws \Exception
     * @return void
     */
    public function overrideTagMapping($tag, $class)
    {
        if (!array_key_exists($tag, $this->tagMapping)) {
            throw new \Exception(sprintf('Tag "%s" is not mapped to a class. 
            You must use the addTagMapping() method.', $tag));
        }
        $this->tagMapping[$tag] = $class;
    }

    /**
     * @param string $tag
     * @throws \Exception
     * @return void
     */
    public function removeTagMapping($tag)
    {
        if (!array_key_exists($tag, $this->tagMapping)) {
            throw new \Exception(sprintf('Tag "%s" is not mapped to a class.', $tag));
        }
        unset($this->tagMapping[$tag]);
    }

    /**
     * @param string $tag
     * @return ElementInterface
     */
    public function instanceElementByTagName($tag)
    {
        if (array_key_exists($tag, $this->tagMapping)) {
            // use defined tag equivalent class
            return $this->instanceNode($this->tagMapping[$tag]);
        } else {
            return $this->instanceNodeByType(self::NODE_ELEMENT);
        }
    }

    /**
     * @param string $attribute
     * @param string $class
     * @throws \Exception
     * @return void
     */
    public function addAttributeMapping($attribute, $class)
    {
        if (array_key_exists($attribute, $this->attributeMapping)) {
            throw new \Exception(sprintf('Attribute "%s" is already mapped to class "%s". 
            You can override it by using overrideAttributeMapping() method.', $attribute, $this->attributeMapping[$attribute]));
        }

        $this->attributeMapping[$attribute] = $class;
    }

    /**
     * @param string $attribute
     * @param string $class
     * @throws \Exception
     * @return void
     */
    public function overrideAttributeMapping($attribute, $class)
    {
        if (!array_key_exists($attribute, $this->attributeMapping)) {
            throw new \Exception(sprintf('Attribute "%s" is not mapped to a class. 
            You must use the addAttributeMapping() method.', $attribute));
        }
        $this->attributeMapping[$attribute] = $class;
    }

    /**
     * @param string $attribute
     * @throws \Exception
     * @return void
     */
    public function removeAttributeMapping($attribute)
    {
        if (!array_key_exists($attribute, $this->attributeMapping)) {
            throw new \Exception(sprintf('Attribute "%s" is not mapped to a class.', $attribute));
        }
        unset($this->attributeMapping[$attribute]);
    }

    /**
     * @param string $attributeName
     * @return AttributeInterface
     */
    public function instanceAttributeByName($attributeName)
    {
        if (array_key_exists($attributeName, $this->attributeMapping)) {
            return new $this->attributeMapping[$attributeName];
        } else {
            return $this->instanceNodeByType(self::NODE_ATTRIBUTE);
        }
    }

    /**
     * @param string $filename
     * @return void
     */
    public function loadFromFile($filename)
    {
        $this->parser->loadFromFile($filename);
    }

    /**
     * @param string $string
     * @return void
     */
    public function loadFromString($string)
    {
        $this->parser->loadFromString($string);
    }

    /**
     * @return TextInterface
     */
    public function parse()
    {
        return $this->parser->parse();
    }

    /**
     * Return PHP valid source code
     * 
     * @return string
     */
    public function compile()
    {
        $node = $this->parse();
        return $node->compile();
    }

    /**
     * @param string $file File path for compiled code
     * @return bool
     */
    public function compileToFile($file)
    {
        $data   = [];
        $data[] = '<?php' . PHP_EOL;
        $data[] = PHP_EOL;
        $data[] = '//' . PHP_EOL;
        $data[] = '// This file is generated by Aloha Template Engine' . PHP_EOL;
        $data[] = sprintf('// It\'s compiled on %s', (new \DateTime())->format('Y-m-d H:i:s')) . PHP_EOL;
        $data[] = '//' . PHP_EOL;
        $data[] = PHP_EOL;
        $data[] = $this->compile();
        $code   = implode('', $data);

        return file_put_contents($file, $code);
    }

}
