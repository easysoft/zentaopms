<?php
declare(strict_types=1);
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

require_once __DIR__ . DS . 'node.class.php';
require_once __DIR__ . DS . 'text.class.php';
require_once __DIR__ . DS . 'directive.class.php';

class h extends node
{
    public static array $defineProps = array
    (
        'tagName'   => 'string',
        'selfClose' => '?bool'
    );

    public function tagName(): string
    {
        $tagName = $this->prop('tagName');
        return $tagName === null ? '' : $tagName;
    }

    public function type(): string
    {
        return 'h::' . $this->tagName();
    }

    public function shortType(): string
    {
        return $this->tagName();
    }

    public function isSelfClose(): bool
    {
        $selfClose = $this->prop('selfClose');
        if($selfClose !== null) return boolval($selfClose);

        return in_array($this->tagName(), static::$selfCloseTags);
    }

    protected function onSetProp(array|string $prop, mixed $value)
    {
        if($prop === 'className') $prop = 'class';
        return parent::onSetProp($prop, $value);
    }

    public function build(): mixed
    {
        if($this->isSelfClose()) return $this->buildSelfCloseTag();

        return array($this->buildTagBegin(), parent::build(), $this->buildTagEnd());
    }

    protected function getPropsStr(): string
    {
        $propStr = $this->props->toStr(array_keys(static::definedPropsList()));
        if($this->props->hasEvent() && empty($this->id()) && $this->tagName() !== 'html') $propStr = "$propStr id='$this->gid'";
        return empty($propStr) ? '' : " $propStr";
    }

    protected function buildSelfCloseTag(): string
    {
        $tagName = $this->tagName();
        $propStr = $this->getPropsStr();
        return "<$tagName$propStr />";
    }

    protected function buildTagBegin(): string
    {
        $tagName = $this->tagName();
        $propStr = $this->getPropsStr();
        return "<$tagName$propStr>";
    }

    protected function buildTagEnd(): string
    {
        $tagName = $this->tagName();
        return "</$tagName>";
    }

    public static function create(string $tagName, mixed ...$args): h
    {
        $h = new h(...$args);
        $h->setProp('tagName', $tagName);
        return $h;
    }

    public static function __callStatic(string $tagName, array $args): h
    {
        return static::create($tagName, ...$args);
    }

    public static function a(): h
    {
        $a = static::create('a', func_get_args());
        if($a->prop('target') === '_blank' && !$a->hasProp('rel'))
        {
            $a->setProp('rel', 'noopener noreferrer');
        }
        return $a;
    }

    public static function button(mixed ...$args): h
    {
        $button = static::create('button', ...$args);
        $button->setDefaultProps('type', 'button');
        return $button;
    }

    public static function input(mixed ...$args): h
    {
        $input = static::create('input', ...$args);
        $input->setDefaultProps('type', 'text');
        return $input;
    }

    public static function formHidden(string $name, string $value, mixed ...$args): h
    {
        $input = static::create('input', ...$args);
        $input->setDefaultProps(array('type' => 'hidden', 'name' => $name, 'value' => $value));
        return $input;
    }

    public static function checkbox(mixed ...$args): h
    {
        $input = static::create('input', ...$args);
        $input->setDefaultProps('type', 'checkbox');
        return $input;
    }

    public static function radio(mixed ...$args): h
    {
        $input = static::create('input', ...$args);
        $input->setDefaultProps('type', 'radio');
        return $input;
    }

    public static function textarea(mixed ...$args)
    {
        list($code, $args) = h::splitRawCode($args);
        return static::create('textarea', $code, $args);
    }

    /**
     * create a html comment tag <!--...-->
     *
     * @access public
     * @param  string $comment
     * @return node
     */
    public static function comment(string $comment): text
    {
        return html("<!-- $comment -->");
    }

    public static function importJs(string $src, mixed ...$args): h
    {
        $script = static::create('script', ...$args);
        $script->setDefaultProps('src', 'src');
        return $script;
    }

    public static function importCss(string $href, mixed ...$args): h
    {
        $link = static::create('link', ...$args);
        $link->setDefaultProps(array('rel' => 'stylesheet', 'href' => $href));
        return $link;
    }

    public static function import(string|array $file, ?string $type = null, mixed ...$args): ?h
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
        if($type === null) $type = pathinfo($file, PATHINFO_EXTENSION);
        if($type == 'js' || $type == 'cjs') return static::importJs($file, $args);
        if($type == 'css') return static::importCss($file, $args);
        return null;
    }

    public static function css(mixed ...$args): ?h
    {
        list($code, $args) = h::splitRawCode($args);
        if(empty($code)) return null;
        return static::create('style', html(...$code), $args);
    }

    public static function globalJS(mixed ...$args): ?h
    {
        list($code, $args) = h::splitRawCode($args);
        if(empty($code)) return null;
        return static::create('script', html(...$code), $args);
    }

    public static function js(mixed ...$args): ?h
    {
        list($code, $args) = h::splitRawCode($args);
        if(empty($code)) return null;
        $code = ';(function(){' . implode("\n", $code) . '}());';
        return static::create('script', html($code, $args));
    }

    public static function jsVar(string $name, mixed $value, mixed ...$args): ?h
    {

        return static::js(js()->var($name, $value), $args);
    }

    public static function jsCall(string $funcName, mixed ...$args): h
    {
        $args  = func_get_args();
        $funcName  = array_shift($args);

        $funcArgs   = array();
        $directives = array();
        foreach($args as $arg)
        {
            if(isDirective($arg)) $directives[] = $arg;
            else                  $funcArgs[] = $arg;
        }

        $js = js()->call($funcName, ...$funcArgs);
        return static::js($js->toJS(), $directives);
    }

    protected static function splitRawCode($children)
    {
        $children = \zin\utils\flat($children);
        $code = array();
        $args = array();
        foreach($children as $child)
        {
            if(is_string($child)) $code[] = $child;
            else                  $args[] = $child;
        }
        return array($code, $args);
    }

    public static $selfCloseTags = array('area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr');
}
