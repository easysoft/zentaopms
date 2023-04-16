<?php
/**
 * The zui component class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(__DIR__) . DS . 'core' . DS . 'wg.func.php';
require_once dirname(__DIR__) . DS . 'core' . DS . 'wg.func.php';
require_once 'toggle.func.php';
require_once 'toggle.class.php';

class zui extends wg
{
    static $defineProps = '_name:string, _to?:string, _tag:string="div", _toProps?: array';

    protected function build()
    {
        list($name, $target, $tagName, $targetProps) = $this->prop(array('_name', '_to', '_tag', '_toProps'));
        $selector = empty($target) ? "[data-zin-id='$this->gid']" : $target;
        $options = $this->props->skip(array_keys(static::getDefinedProps()));
        return array
        (
            empty($target) ? h
            (
                $tagName,
                set($targetProps),
                set('data-zin-id', $this->gid)
            ) : NULL,
            $this->children(),
            h::jsCall('~zui.create', $name, $selector, $options)
        );
    }

    public static function __callStatic($name, $args)
    {
        return new zui(set('_name', $name), $args);
    }

    public static function toggle($name, $options = NULL)
    {
        return toggle($name, $options);
    }

    public static function setClass($name, ...$args)
    {
        $class = [$name => true];
        foreach($args as $arg)
        {
            if(is_bool($arg)) $class[$name]        = $arg;
            else              $class["$name-$arg"] = true;
        }
        if(isset($class[$name]) && $class[$name] === false) return NULL;
        return setClass($class);
    }

    public static function skin($name, $flag = true, $falseValue = NULL, $cssProp = NULL)
    {
        if($flag === NULL) return NULL;

        if(is_array($flag))
        {
            return array_map(function($value) use($name, $cssProp) {return zui::skin($name, $value, NULL, $cssProp);}, $flag);
        }

        if($flag === false)
        {
            if(empty($falseValue)) return NULL;
            $flag = 'none';
        }
        elseif($cssProp !== NULL && is_string($flag) && (str_ends_with($flag, 'px') || str_starts_with($flag, '#') || str_contains($flag, '.') || str_contains($flag, '(')))
        {
            if(str_starts_with($flag, '(')) $flag = substr($flag, 1, -1);
            return setStyle($cssProp, $flag);
        }
        return setClass($flag === true ? $name : "$name-$flag");
    }

    public static function rounded($value = true)
    {
        return zui::skin('rounded', $value, 'none', 'border-radius');
    }

    public static function shadow($value = true)
    {
        return zui::skin('shadow', $value, 'none');
    }

    public static function primary($value = true)
    {
        return zui::skin('primary', $value);
    }

    public static function secondary($value = true)
    {
        return zui::skin('secondary', $value);
    }

    public static function success($value = true)
    {
        return zui::skin('success', $value);
    }

    public static function warning($value = true)
    {
        return zui::skin('warning', $value);
    }

    public static function danger($value = true)
    {
        return zui::skin('danger', $value);
    }

    public static function important($value = true)
    {
        return zui::skin('important', $value);
    }

    public static function special($value = true)
    {
        return zui::skin('special', $value);
    }

    public static function bg($value = NULL)
    {
        return zui::skin('bg', $value, 'transparent', 'background');
    }

    public static function text($value = NULL)
    {
        return zui::skin('text', $value, 'fore', 'color');
    }

    public static function muted($value = true)
    {
        return $value ? setClass('muted') : NULL;
    }

    public static function opacity($value)
    {
        return zui::skin('opacity', $value, '0', 'opacity');
    }

    public static function disabled($value = true)
    {
        return $value ? setClass('disabled') : NULL;
    }

    public static function width($value)
    {
        return zui::skin('w', $value, '0', 'width');
    }

    public static function height($value)
    {
        return zui::skin('h', $value, '0', 'width');
    }

    public static function ring(...$args)
    {
        return zui::skin('ring', $args, '0');
    }

    public static function border(...$args)
    {
        return zui::skin('border', $args, 'none', 'border');
    }
}
