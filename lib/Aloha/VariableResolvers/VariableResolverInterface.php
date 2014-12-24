<?php

namespace Aloha\VariableResolvers;

/**
 * @author VM5 Ltd. <office@vm5.bg>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.bg/)
 */
interface VariableResolverInterface
{

    /**
     * @return string
     */
    public function getId();

    /**
     * @param string $string
     */
    public function setString($string);

    /**
     * @param string $variable
     * @param mixed $value
     */
    public function setVariable($variable, $value);

    /**
     * @param array $variables
     */
    public function setVariables($variables);

    /**
     * @return string
     */
    public function resolve();

    /**
     * @return string
     */
    public function compile();
}
