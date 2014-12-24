<?php

namespace Aloha\Nodes;

/**
 * @author VM5 Ltd. <office@vm5.bg>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.bg/)
 */
interface ElementInterface extends NodeInterface
{

    const TAG_OPEN         = '<%s%s>';
    const TAG_CLOSE        = '</%s>';
    const TAG_SELF_CLOSING = '<%s%s />';
    const TAG_VOID         = '<%s%s>';

    /**
     * @param string $tag
     * @return void
     */
    public function setTag($tag);

    /**
     * @return string
     */
    public function getTag();

    /**
     * @param AttributeInterface[] $attributes
     * @return void
     */
    public function setAttributes($attributes);

    /**
     * @return AttributeInterface[]
     */
    public function getAttributes();

    /**
     * @param bool $isSelfClosing
     * @return void
     */
    public function setIsSelfClosing($isSelfClosing);

    /**
     * @return bool
     */
    public function getIsSelfClosing();

    /**
     * @param bool $isVoid
     * @return void
     */
    public function setIsVoid($isVoid);

    /**
     * @return bool
     */
    public function getIsVoid();
}
