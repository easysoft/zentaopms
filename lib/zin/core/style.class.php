<?php
declare(strict_types=1);
/**
 * The style setter class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'dataset.class.php';

class style extends \zin\utils\dataset implements iDirective
{
    /**
     * Magic method for setting style.
     *
     * @access public
     * @param  string $name - Property name.
     * @param  array  $args - Property values.
     * @return style
     */
    public function __call(string $name, array $args): style
    {
        $value = empty($args) ? true : (count($args) === 1 ? $args[0] : $args);
        return $this->setVal($name, $value);
    }

    /**
     * Method for sub class to hook on setting it.
     *
     * @access protected
     * @param string    $name         Property name or properties list.
     * @param mixed     $value        Property value.
     * @return style
     */
    protected function setVal(string $name, mixed $value): style
    {
        $name = static::formatStyleName($name);
        if($value === null)
        {
            $this->storedData[$name] = $value;
            return $this;
        }

        if(!is_bool($value)) $value = static::formatStyleValue($name, $value);

        $this->storedData[$name] = $value;
        return $this;
    }

    public function apply(node $node, string $blockName): void
    {
        $style = array();
        $class = array();

        foreach ($this->storedData as $name => $value)
        {
            if(is_bool($value)) $class[$name] = $value;
            else                $style[$name] = $value;
        }

        if($style) $node->setProp('style', $style);
        if($class) $node->setProp('class', $class);
    }

    public function var(string|array $nameOrMap, mixed $value = null): style
    {
        if(is_array($nameOrMap))
        {
            foreach($nameOrMap as $name => $val) $this->setVal('--' . $name, $val);
            return $this;
        }
        return $this->setVal('--' . $nameOrMap, $value);
    }

    /**
     * Magic static method for style property value.
     *
     * @access public
     * @param  string $name  - Property name.
     * @param  array  $args  - Property values.
     * @return style
     */
    public static function __callStatic($name, $args): style
    {
        $style = new style();

        $style->$name(...$args);

        return $style;
    }

    public static function formatStyleName(string $name): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', str_replace('_', '-', $name)));
    }

    public static function formatStyleValue(string $name, null|string|int|array $value): string
    {
        if(is_array($value))
        {
            $valueList = array();
            foreach($value as $v)
            {
                $valueList[] = self::formatStyleValue($name, $v);
            }
            return implode(' ', $valueList);
        }

        if(is_null($value)) return '';

        if(!is_string($value) && is_numeric($value) && (str_ends_with($name, '-width') || str_ends_with($name, '-height') || str_ends_with($name, '-radius') || in_array($name, array('width', 'height', 'radius', 'top', 'left', 'right', 'bottom', 'inset'))))
        {
            $value .= 'px';
        }
        elseif(is_string($value) && str_starts_with($value, '--'))
        {
            $value = 'var(' . $value . ')';
        }

        return is_string($value) ? $value : (string)$value;
    }
}

/**
 * Set widget style attribute.
 *
 * @return set
 */
function setStyle(array|string $name, ?string $value = null): style
{
    $style = new style($name, $value);
    return $style;
}
