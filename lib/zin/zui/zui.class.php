<?php
declare(strict_types=1);
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
require_once __DIR__ . DS . 'toggle.func.php';
require_once __DIR__ . DS . 'toggle.class.php';

class zui extends wg
{
    protected static array $defineProps = array(
        '_name:string',
        '_to?:string',
        '_tag:string="div"',
        '_map?:array',
        '_props?: array',
        '_options?: array',
        '_size?: array',
        '_id?: string',
        '_class?: string',
        '_call?: string',
        '_initWithShareData?: bool',
    );

    protected function build(): wg|array
    {
        list($name, $target, $tagName, $targetProps, $size, $id, $class, $map, $call, $initWithShareData, $userOptions) = $this->prop(array('_name', '_to', '_tag', '_props', '_size', '_id', '_class', '_map', '_call', '_initWithShareData', '_options'));
        list($width, $height) = $size;

        $options  = $this->getRestProps();
        $children = $this->children();

        if(is_array($map))
        {
            foreach($options as $key => $value)
            {
                if(!isset($map[$key])) continue;
                $options[$map[$key]] = $value;
                unset($options[$key]);
            }
        }

        if($initWithShareData && empty($call) && empty($target))
        {
            if(empty($id)) $id = $this->gid;
            $optionsName = "_options_$id";
            $children[] = setData(array('zui' => "$name:$optionsName"));
            $children[] = h::jsShare($optionsName, $options);
        }
        else
        {
            if(empty($call)) $call = '~zui.create';
            $selector = $target;
            if(empty($selector))
            {
                if(empty($id)) $id = $this->gid;
                $selector = "#$id";
            }
            if(is_array($userOptions)) $options = array_merge($options, $userOptions);
            $children[] = h::jsCall($call, $name, $selector, $options);
        }

        if(empty($target))
        {
            return h
            (
                $tagName,
                setClass($class),
                setID($id),
                setStyle('width', $width),
                setStyle('height', $height),
                set($targetProps),
                $children,
            );
        }

        return  $children;
    }

    public static function __callStatic($name, $args)
    {
        return new zui(set('_name', $name), $args);
    }

    public static function toggle($name, $options = array())
    {
        return toggle($name, $options);
    }

    public static function setClass(/* $name, ...$args */)
    {
        $args = func_get_args();
        $name = array_shift($args);
        $class = array($name => true);
        foreach($args as $arg)
        {
            if(is_bool($arg)) $class[$name]        = $arg;
            else              $class["$name-$arg"] = true;
        }
        if(isset($class[$name]) && $class[$name] === false) return null;
        return setClass($class);
    }

    public static function skin($name, $flag = true, $falseValue = null, $cssProp = null)
    {
        if($flag === null) return null;

        if(is_array($flag))
        {
            return array_map(function($value) use($name, $cssProp) {return zui::skin($name, $value, null, $cssProp);}, $flag);
        }

        if($flag === false)
        {
            if(empty($falseValue)) return null;
            $flag = 'none';
        }
        elseif($cssProp !== null && is_string($flag) && (str_ends_with($flag, 'px') || str_starts_with($flag, '#') || str_contains($flag, '.') || str_contains($flag, '(')))
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

    public static function bg($value = null)
    {
        return zui::skin('bg', $value, 'transparent', 'background');
    }

    public static function text($value = null)
    {
        return zui::skin('text', $value, 'fore', 'color');
    }

    public static function muted($value = true)
    {
        return $value ? setClass('muted') : null;
    }

    public static function opacity($value)
    {
        return zui::skin('opacity', $value, '0', 'opacity');
    }

    public static function disabled($value = true)
    {
        return $value ? setClass('disabled') : null;
    }

    public static function width($value)
    {
        return zui::skin('w', $value, '0', 'width');
    }

    public static function height($value)
    {
        return zui::skin('h', $value, '0', 'height');
    }

    public static function ring(/* ...$args */)
    {
        return zui::skin('ring', func_get_args(), '0');
    }

    public static function border(/* ...$args */)
    {
        return zui::skin('border', func_get_args(), 'none', 'border');
    }
}
