<?php

namespace Aloha\Nodes;

/**
 * @author VM5 Ltd. <office@vm5.bg>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.bg/)
 */
interface DoctypeInterface extends NodeInterface
{

    const ID_TYPE_NONE    = '';
    const ID_TYPE_PUBLIC  = 'PUBLIC';
    const ID_TYPE_SYSTEM  = 'SYSTEM';
    const DOCTYPE_PATTERN = '<!DOCTYPE %s%s%s%s>';

    /**
     * 
     * @param string $name
     */
    public function setRootElementName($name);

    /**
     * @param string $type
     */
    public function setIdType($type);

    /**
     * 
     * @param string $id
     */
    public function setPublicId($id);

    /**
     * 
     * @param string $id
     */
    public function setSystemId($id);
}
