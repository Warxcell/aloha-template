<?php

namespace Aloha\Parsers;

use Aloha\Template;
use Aloha\TextInterface;

/**
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @author VM5 Ltd. <office@vm5.bg>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.bg/)
 */
interface ParserInterface {

    /**
     * @param Template $template
     */
    public function setTemplate(Template $template);

    /**
     * @param string $file
     */
    public function loadFromFile($file);

    /**
     * @param string $string
     */
    public function loadFromString($string);

    /**
     * @return TextInterface
     */
    public function parse();
}
