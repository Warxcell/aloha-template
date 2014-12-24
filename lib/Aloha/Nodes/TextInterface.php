<?php

namespace Aloha\Nodes;

/**
 * @author VM5 Ltd. <office@vm5.bg>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.bg/)
 */
interface TextInterface extends NodeInterface {

    /**
     * @param string $content
     */
    public function setContent($content);

    /**
     * @return string
     */
    public function getContent();
}
