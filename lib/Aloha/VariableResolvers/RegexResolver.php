<?php

namespace Aloha\VariableResolvers;

/**
 * Replace template variables with regex implementation
 * 
 * Variables can be:
 * 
 * {variable}
 * or
 * {variable.subvariable}
 * or
 * {variable.subvariable.subsubvariable.[...]}
 *
 *  
 * 
 * @author VM5 Ltd. <office@vm5.eu>
 * @author Ivan Slavkov <ivan.slavkov@gmail.com>
 * @copyright (c) 2014, VM5 Ltd. (http://www.vm5.eu/)
 */
class RegexResolver implements VariableResolverInterface
{

    /**
     * @var string 
     */
    protected $formatMask = '/\{([a-z0-9\.]+)\}/i';

    /**
     * @var string
     */
    protected $string;

    /**
     * @var mixed[]
     */
    protected $variables = [];

    /**
     * @return string
     */
    public function getId()
    {
        return 'variableResolver' . spl_object_hash($this);
    }

    /**
     * @param string $string
     */
    public function setString($string)
    {
        $this->string = $string;

        return $this;
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @param array $variables
     */
    public function setVariables($variables)
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * @param string $variable
     * @param mixed $value
     */
    public function setVariable($variable, $value)
    {
        $this->variables[$variable] = $value;

        return $this;
    }

    /**
     * @return string
     */
    function getFormatMask()
    {
        return $this->formatMask;
    }

    /**
     * Variable style regex mask
     * 
     * @param string $formatMask
     * @return void
     */
    function setFormatMask($formatMask)
    {
        $this->formatMask = $formatMask;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve()
    {

        return preg_replace_callback($this->formatMask, function($matches) {

            $value = '';

            if (array_key_exists($matches[1], $this->variables)) {
                $value = $this->variables[$matches[1]];
            } else {
                $subvariables = explode('.', $matches[1]);
                $currentArray = null;
                foreach ($subvariables as $subvariable) {
                    if ($currentArray == null) {
                        $currentArray = $this->variables;
                    }

                    if (array_key_exists($subvariable, $currentArray)) {
                        $val = $currentArray[$subvariable];
                        if (is_array($val)) {
                            $currentArray = $val;
                        } else {
                            $value = $val;
                            break;
                        }
                    }
                }
            }

            return htmlentities($value);
        }, $this->string);
    }

    /**
     * {@inheritdoc}
     */
    public function compile()
    {
        $objectId = $this->getId();

        $output   = [];
        $output[] = sprintf('$%s = new %s();', $objectId, get_class($this));

        return implode(PHP_EOL, $output);
    }

}
