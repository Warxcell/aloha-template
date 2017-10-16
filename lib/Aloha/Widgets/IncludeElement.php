<?php

namespace Aloha\Widgets;

use Aloha\Nodes\Element;
use Aloha\Nodes\NodeInterface;
use Aloha\Template;

/**
 * @author VM5 Ltd. <office@vm5.eu>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2017, VM5 Ltd. (http://www.vm5.eu/)
 */
class IncludeElement extends Element
{

    const TAG_NAME = 'aloha-include';

    /**
     * @var NodeInterface
     */
    protected $parsed;

    /**
     * IncludeElement constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->tag = self::TAG_NAME;
        $this->isSelfClosing = true;
        $this->isVoid = false;
    }

    /**
     * @throws \Exception
     */
    protected function doInclude(){
        if(!$this->hasAttribute('file')){
            throw new \Exception(sprintf('%s used without "file" attribute', self::TAG_NAME));
        }

        $fileAttribute = $this->getAttribute('file');
        $fileName = $fileAttribute->getValue();

        if(!is_file($fileName)){
            throw new \Exception(sprintf('File "%s" does not exists!', $file));
        }

        $template = new Template();
        $template->loadFromFile($fileName);
        $this->parsed = $template->parse();

        foreach($this->variables as $name => $variable){
            $this->parsed->setVariable($name, $variable);
        }

        $this->addChild($this->parsed);
    }

    /**
     * @return string
     */
    public function paste(){
        $this->doInclude();
        return $this->parsed->paste();
    }


}