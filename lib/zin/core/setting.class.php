<?php
declare(strict_types=1);
/**
 * The setting class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'dataset.class.php';
require_once dirname(__DIR__) . DS . 'utils' . DS . 'classlist.class.php';

class setting extends \zin\utils\dataset
{
    /**
     * Set property values.
     *
     * @access public
     * @param  string $name  - Property name.
     * @param  array  $args  - Property values.
     * @return setting
     */
    public function setValues(string $name, mixed ...$args): setting
    {
        if(empty($args))
        {
            $value = true;
        }
        elseif(($name === 'url' || $name === 'href' || $name === 'link') && count($args) > 1)
        {
            /* Support to set url with createLink params. */
            $value = call_user_func_array('\helper::createLink', $args);
        }
        else if(count($args) > 1)
        {
            $value = array();
            foreach($args as $key => $val)
            {
                $value[$key] = ($val instanceof setting) ? $val->toArray() : $val;
            }
        }
        else
        {
            $value = array_shift($args);
        }
        return $this->setVal($name, $value);
    }

    /**
     * Set property value as class list.
     *
     * @access public
     * @param  string $name  - Property name.
     * @param  mixed  $class - Class list.
     * @return setting
     */
    public function setClass(string $name, mixed ...$class): setting
    {
        if(empty($class)) return $this;

        if(isset($class[0]) && $class[0] === true)
        {
            return $this->setVal($name, \zin\utils\classlist::format($class));
        }
        return $this->setVal($name, \zin\utils\classlist::format($this->getVal($name), $class));
    }

    /**
     * Method for sub class to hook on setting it.
     *
     * @access protected
     * @param string    $prop         Property name or properties list.
     * @param mixed     $value        Property value.
     * @return setting
     */
    protected function setVal(string $prop, mixed $value): setting
    {
        if($value instanceof setting) $value = $value->toArray();
        $this->storedData[$prop] = $value;
        return $this;
    }

    /**
     * Convert to directive.
     *
     * @access protected
     * @param  string $type  - Directive type.
     * @return directive
     */
    public function toDirective(string $type = 'prop'): directive
    {
        return new directive($type, $this->storedData);
    }

    /**
     * Magic method for setting property value.
     *
     * @access public
     * @param  string $name  - Property name.
     * @param  array  $args  - Property values.
     * @return setting
     */
    public function __call(string $name, array $args): setting
    {
        return $this->setValues($name, ...$args);
    }

    /**
     * Magic static method for setting property value.
     *
     * @access public
     * @param  string $name  - Property name.
     * @param  array  $args  - Property values.
     * @return setting
     */
    public static function __callStatic($name, $args): setting
    {
        /* Compatible with zui prop className. */
        if($name === '_className') $name = 'className';

        $set = new setting();

        return $set->setValues($name, ...$args);
    }
}

/**
 * Create a setting instance.
 *
 * @param  array|string $setting  - Setting data or setting property name.
 * @param  mixed        $value    - Setting value.
 * @return setting
 */
function setting(?array|string $setting = null, mixed $value = null): setting
{
    return new setting($setting, $value);
}

/**
 * Convert setting to array.
 *
 * @param  array|setting $setting  - Setting data or setting instance.
 * @return array
 */
function toArray(array|setting $setting): array
{
    return $setting instanceof setting ? $setting->toArray() : $setting;
}

/**
 * Convert setting list to array.
 *
 * @param  array $array  - Array data.
 * @return setting
 */
function toArrayList(array $array): array
{
    $list = array();
    foreach($array as $key => $val)
    {
        $list[$key] = $val instanceof setting ? $val->toArray() : $val;
    }
    return $list;
}
