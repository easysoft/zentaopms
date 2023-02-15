<?php
/**
 * The html element class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'flat.func.php';
require_once 'wg.class.php';
require_once 'directive.func.php';

class h extends wg
{
    protected static $defineProps = 'tagName, selfClose?:bool=false, customProps?:string|array';

    public function getTagName()
    {
        return $this->props->get('tagName');
    }

    public function isSelfClose()
    {
        $selfClose = $this->props->get('selfClose');
        if($selfClose !== NULL) return $selfClose;

        return in_array($this->getTagName(), array('area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'));
    }

    public function build()
    {
        if($this->isSelfClose()) return $this->buildSelfCloseTag();

        return array($this->buildTagBegin(), parent::build(), $this->buildTagEnd());
    }

    protected function getPropsStr()
    {
        $skipProps   = array_keys(static::getDefinedProps());
        $customProps = $this->props->get('customProps');

        if($customProps) $skipProps = array_merge($skipProps, is_string($customProps) ? explode(',', $customProps) : $customProps);

        $propStr = $this->props->toStr($skipProps);
        return empty($propStr) ? '' : " $propStr";
    }

    protected function buildSelfCloseTag()
    {
        $tagName = $this->getTagName();
        $propStr = $this->getPropsStr();
        return "<$tagName$propStr />";
    }

    protected function buildTagBegin()
    {
        $tagName = $this->getTagName();
        $propStr = $this->getPropsStr();
        return "<$tagName$propStr>";
    }

    protected function buildTagEnd()
    {
        $tagName = $this->getTagName();
        return "</$tagName>";
    }

    public static function create($tagName, $args, $defaultProps = NULL)
    {
        $args = func_get_args();
        $tagName = array_shift($args);
        return new h(is_string($tagName) ? prop('tagName', $tagName) : $tagName, $args);
    }

    public static function __callStatic($tagName, $args)
    {
        return new h(prop('tagName', $tagName), $args);
    }

    public static function button()
    {
        return static::create('button', prop('type', 'button'), func_get_args());
    }

    public static function input()
    {
        return static::create('input', prop('type', 'text'), func_get_args());
    }

    public static function checkbox()
    {
        return static::create('input', prop('type', 'checkbox'), func_get_args());
    }

    public static function radio()
    {
        return static::create('input', prop('type', 'radio'), func_get_args());
    }

    public static function textarea()
    {
        $children = h::convertStrToRawHtml(func_get_args());
        return static::create('textarea', $children);
    }

    public static function importJs($src)
    {
        return static::create('script', prop('src', $src));
    }

    public static function importCss($src)
    {
        return static::create('link', prop('rel', 'stylesheet'), prop('href', $src));
    }

    public static function import($file, $type = NULL)
    {
        if(is_array($file))
        {
            $children = array();
            foreach($file as $file)
            {
                $children[] = static::import($file, $type);
            }
            return $children;
        }
        if($type === NULL) $type = pathinfo($file, PATHINFO_EXTENSION);
        if($type == 'js' || $type == 'cjs') return static::importJs($file);
        if($type == 'css') return static::importCss($file);
        return null;
    }

    public static function css()
    {
        $children = h::convertStrToRawHtml(func_get_args());
        return static::create('style', $children);
    }

    public static function js()
    {
        $children = h::convertStrToRawHtml(func_get_args());
        return static::create('script', $children);
    }

    protected static function convertStrToRawHtml($children)
    {
        $children = \zin\utils\flat($children);
        foreach($children as $key => $child)
        {
            if(is_string($child)) $children[$key] = html($child);
        }
        return $children;
    }
}
