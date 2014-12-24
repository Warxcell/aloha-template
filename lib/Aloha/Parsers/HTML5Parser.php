<?php

namespace Aloha\Parsers;

use Aloha\Template;
use Aloha\Nodes\NodeInterface;
use Aloha\Nodes\DoctypeInterface;
use Aloha\Nodes\TextInterface;
use Aloha\Nodes\CommentInterface;
use Masterminds\HTML5\Parser\EventHandler;
use Masterminds\HTML5\Parser\Tokenizer;
use Masterminds\HTML5\Parser\StringInputStream;
use Masterminds\HTML5\Parser\Scanner;

/**
 * @author VM5 Ltd. <office@vm5.bg>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.bg/)
 */
class HTML5Parser implements EventHandler, ParserInterface {

    /**
     * @var Tokenizer
     */
    protected $tokenizer;

    /**
     * @var Template
     */
    protected $template;

    /**
     * @var NodeInterface[] 
     */
    protected $currentNodes = [];

    /**
     * @var int
     */
    protected $depth = 0;

    /**
     * Void elements can't have any contents 
     * (since there's no end tag, no content can be put between the start tag and the end tag).
     * 
     * http://www.w3.org/html/wg/drafts/html/master/single-page.html#void-elements
     * 
     * @var string[]
     */
    private $voidElementTags = [
        'area',
        'base',
        'br',
        'col',
        'embed',
        'hr',
        'img',
        'input',
        'keygen',
        'link',
        'menuitem',
        'meta',
        'param',
        'source',
        'track',
        'wbr'
    ];

    /**
     * @param string $tag
     * @return bool
     */
    protected function isVoid($tag) {
        return in_array($tag, $this->voidElementTags);
    }

    /**
     * @inheritdoc
     */
    public function loadFromFile($file) {
        if ($file != null) {
            $this->loadFromString(file_get_contents($file));
        }
    }

    /**
     * @inheritdoc
     */
    public function loadFromString($string) {
        $input = new StringInputStream($string);
        $scanner = new Scanner($input);
        $this->tokenizer = new Tokenizer($scanner, $this);
    }

    /**
     * @inheritdoc
     */
    public function parse() {
        // root element
        $this->currentNodes[$this->depth] = $this->template->instanceNodeByType(Template::NODE_TEXT);
        $this->tokenizer->parse();
        return $this->currentNodes[$this->depth];
    }

    /**
     * @param Template $template
     * @return void
     */
    public function setTemplate(Template $template) {
        $this->template = $template;
    }

    /**
     * @return void
     */
    public function eof() {
        // ignore for now...
    }

    /**
     * @param type $msg
     * @param type $line
     * @param type $col
     * @return void
     */
    public function parseError($msg, $line, $col) {
        $message = 'XML error: %s at line %d, column %d';
        $error = sprintf($message, $msg, $line, $col);
        throw new \Exception($error);
    }

    /**
     * @param string $name
     * @param string $data
     * @return void
     */
    public function processingInstruction($name, $data = null) {
        // ignore for now...
    }

    /**
     * @param string $name
     * @param string $idType
     * @param string $id
     * @param bool $quirks
     * @return void
     */
    public function doctype($name, $idType = 0, $id = null, $quirks = false) {
        $idTypeMapping = [
            self::DOCTYPE_NONE => DoctypeInterface::ID_TYPE_NONE,
            self::DOCTYPE_PUBLIC => DoctypeInterface::ID_TYPE_PUBLIC,
            self::DOCTYPE_SYSTEM => DoctypeInterface::ID_TYPE_SYSTEM
        ];

        /* @var $doctypeNode DoctypeInterface */
        $doctypeNode = $this->template->instanceNodeByType(Template::NODE_DOCTYPE);
        $doctypeNode->setRootElementName($name);
        $doctypeNode->setIdType($idTypeMapping[$idType]);
        $doctypeNode->setPublicId($id);

        $this->currentNodes[$this->depth]->addChild($doctypeNode);
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param bool $selfClosing
     * @return void
     */
    public function startTag($name, $attributes = array(), $selfClosing = false) {
        $element = $this->template->instanceElementByTagName($name);

        $nodeAttributes = [];

        foreach ($attributes as $key => $value) {
            $attribute = $this->template->instanceAttributeByName($key);
            $attribute->setElement($element);
            $attribute->setKey($key);
            $attribute->setValue($value);
            $nodeAttributes[$key] = $attribute;
        }

        $element->setTag($name);
        $element->setAttributes($nodeAttributes);
        if ($this->isVoid($name)) {
            $element->setIsVoid(true);
        }
        $element->setIsSelfClosing($selfClosing);

        $this->currentNodes[$this->depth]->addChild($element); // add this element to it's parent

        if ($this->isVoid($name)) {
            return;
        }

        $this->depth++;
        $this->currentNodes[$this->depth] = $element;
    }

    /**
     * @param string $name
     * @return void
     */
    public function endTag($name) {
        if ($this->isVoid($name)) {
            return;
        }
        $this->depth--;
    }

    /**
     * @param string $cdata
     * @return void
     */
    public function text($cdata) {
        /* @var $node TextInterface */
        $node = $this->template->instanceNodeByType(Template::NODE_TEXT);
        $node->setContent($cdata);

        $this->currentNodes[$this->depth]->addChild($node);
    }

    /**
     * @param string $data
     * @return void
     */
    public function cdata($data) {
        $this->text($data);
    }

    /**
     * @param string $cdata
     * @return void
     */
    public function comment($cdata) {
        /* @var $node CommentInterface */
        $node = $this->template->instanceNodeByType(Template::NODE_COMMENT);
        $node->setContent($cdata);

        $this->currentNodes[$this->depth]->addChild($node);
    }

}
