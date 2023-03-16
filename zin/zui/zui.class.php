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
}
