<?php
declare(strict_types=1);
/**
 * The style class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin\utils;

require_once __DIR__ . DS . 'dataset.class.php';

/**
 * Manage style for html element and widgets
 *
 * Example:
 *
 *     // Create a style object an convert to css string
 *     $style = style::create(array('color' => 'red'));
 *     echo $style(); // Output "color:red"
 *
 *     // Above example same as:
 *     echo style::css(array('color' => 'red'));
 *
 *     // Modifier style
 *     $style = style::create(array('color' => 'red'));
 *     $style->set('background', 'green');
 *
 *     // Modifier style with property name directly
 *     $style->background = 'green';
 *
 *     // Get style value
 *     echo $style->get('background'); // Output "green"
 *
 *     // Get style value with property name directly
 *     echo $style->background; // Output "green"
 *
 * @todo @sunhao: Validate style properties on modifying
 */
class style extends dataset
{
    /**
     * Format CSS variable name with prefix "--"
     *
     * @access public
     * @param string $name - CSS variable name
     * @return string
     */
    public static function formatVarName(string $name): string
    {
        return \zin\str_starts_with($name, '--') ? $name : "--$name";
    }

    /**
     * Set or get css variable, an array can be passed to set multiple variables
     * If only pass variable name, then the variable value will be returned
     * If no params passed, then return all setted variables with an array
     *
     * Notice: no need to prepend prefix '--' to variable name, the method will prepend it automatically, if prepended already, the method will skip to prepend smartly
     *
     * Example:
     *
     *     // Create a style object and set
     *     $style = new style();
     *     $style->cssVar('text-size', '14px');
     *
     *     // Set multiple variables
     *     $style->cssVar(array('text-color' => 'yellow', 'background-image' => 'none'));
     *
     *     // Get variable value
     *     echo $style->cssVar('text-size'); // Output "14px"
     *
     *     // Get all variables value
     *     echo $style->cssVar();
     *     // Output array('text-size' => '14px', 'color' => 'yellow', 'background' => 'none');
     *
     *     // Remove variable by setting value with an empty string
     *     $style->cssVar('text-color', '');
     *
     * @access public
     * @param array|string $name  - Variable name or variables list
     * @param string|null  $value - Property value
     * @return style|array|string
     */
    public function cssVar(array|string $name = '', ?string $value = null): style|array|string
    {
        /* Support for setting multiple variables by an array */
        if(is_array($name))
        {
            foreach($name as $n => $value) $this->set(style::formatVarName($n), $value);
            return $this;
        }

        /* Return all setted variables without passed any params */
        if(empty($name))
        {
            $vars = array();
            foreach ($this->_data as $prop => $value)
            {
                if(!str_starts_with($name, '--')) continue;
                $vars[substr($prop, 2)] = $value;
            }
            return $vars;
        }

        $varName = style::formatVarName($name);

        /* Return the specific variable value by name */
        if($value === null) return $this->get($varName);

        /* Set the specific variable value and return style object self */
        $this->set($varName, $value === '' ? null : $value);
        return $this;
    }

    /**
     * Convert to string
     *
     * @access public
     * @return string
     */
    public function toStr(): string
    {
        $pairs = array();

        foreach($this->_data as $prop => $value)
        {
            /* Skip any empty value */
            if($value === null || $value === '') continue;

            $pairs[] = $prop . ': ' . strval($value) . ';';
        }

        return implode(' ', $pairs);
    }
}
