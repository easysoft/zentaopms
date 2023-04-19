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
require_once 'wg.func.php';

class h extends wg
{
    protected static $defineProps = 'tagName, selfClose?:bool=false';

    public function getTagName()
    {
        return $this->props->get('tagName');
    }

    public function isDomElement()
    {
        return true;
    }

    public function isSelfClose()
    {
        $selfClose = $this->props->get('selfClose');
        if($selfClose !== NULL) return $selfClose;

        return in_array($this->getTagName(), static::$selfCloseTags);
    }

    public function build()
    {
        $events = $this->buildEvents();

        if($this->isSelfClose()) return array($this->buildSelfCloseTag(), $events);

        return array($this->buildTagBegin(), parent::build(), $this->getPortals(), $this->buildTagEnd(), $events);
    }

    public function toJsonData()
    {
        $data = parent::toJsonData();
        $data['type'] = 'h:' . $this->getTagName();
        return $data;
    }

    public function type()
    {
        return $this->getTagName();
    }

    public function shortType()
    {
        return $this->getTagName();
    }

    protected function getPropsStr()
    {
        $propStr = $this->props->toStr(array_keys(static::getDefinedProps()));
        if($this->props->hasEvent() && empty($this->id()) && $this->getTagName() !== 'html') $propStr = "$propStr id='$this->gid'";
        return empty($propStr) ? '' : " $propStr";
    }

    protected function buildEvents()
    {
        $events = $this->props->events();
        if(empty($events)) return NULL;

        $id = $this->id();
        $code = array($this->getTagName() === 'html' ? 'const ele = document;' : 'const ele = document.getElementById("' . (empty($id) ? $this->gid : $id) . '");');
        foreach($events as $event => $bindingList)
        {
            foreach($bindingList as $binding)
            {
                $code[] = "ele.addEventListener('$event', function(e) {";
                if(is_string($binding)) $binding = (object)array('handler' => $binding);
                $selector = isset($binding->selector) ? $binding->selector : NULL;
                $handler  = isset($binding->handler) ? trim($binding->handler) : '';
                $stop  = isset($binding->stop) ? $binding->stop : NULL;
                $prevent  = isset($binding->prevent) ? $binding->prevent : NULL;
                $self  = isset($binding->self) ? $binding->self : NULL;
                unset($binding->selector);
                unset($binding->handler);
                unset($binding->stop);
                unset($binding->prevent);
                unset($binding->self);

                if($selector) $code[] = "if(!e.target.closest('$selector')) return;";
                if($self)     $code[] = "if(ele !== e.target) return;";
                if($stop)     $code[] = "e.stopPropagation();";
                if($prevent)  $code[] = "e.preventDefault();";

                if(preg_match('/^[$A-Z_][0-9A-Z_$\[\]."\']*$/i', $handler)) $code[] = "($handler)(e);";
                else $code[] = $handler;

                $code[] = '}' . (empty($binding) ? '' : (', ' . json_encode($binding))) . ');';
            }
        }
        return static::js($code);
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

    public static function create()
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

    public static function formHidden($name, $value, ...$args)
    {
        return static::create('input', prop('type', 'hidden'), set::name($name), set::value($value), $args);
    }

    public static function checkbox()
    {
        return static::create('input', prop('type', 'checkbox'), func_get_args());
    }

    public static function radio()
    {
        return static::create('input', prop('type', 'radio'), func_get_args());
    }

    public static function date()
    {
        return static::create('input', prop('type', 'date'), func_get_args());
    }

    public static function file()
    {
        return static::create('input', prop('type', 'file'), func_get_args());
    }

    public static function textarea(...$args)
    {
        list($code, $args) = h::splitRawCode($args);
        return static::create('textarea', $code, ...$args);
    }

    public static function importJs($src, ...$args)
    {
        return static::create('script', prop('src', $src), ...$args);
    }

    public static function importCss($src, ...$args)
    {
        return static::create('link', prop('rel', 'stylesheet'), prop('href', $src), ...$args);
    }

    public static function import($file, $type = NULL, ...$args)
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
        if($type == 'js' || $type == 'cjs') return static::importJs($file, ...$args);
        if($type == 'css') return static::importCss($file, ...$args);
        return null;
    }

    public static function css(...$args)
    {
        list($code, $args) = h::splitRawCode($args);
        if(empty($code)) return NULL;
        return static::create('style', html(implode("\n", $code)), ...$args);
    }

    public static function globalJS(...$args)
    {
        list($code, $args) = h::splitRawCode($args);
        if(empty($code)) return NULL;
        return static::create('script', html(implode("\n", $code)), ...$args);
    }

    public static function js(...$args)
    {

        list($code, $args) = h::splitRawCode($args);
        if(empty($code)) return NULL;
        return static::create('script', html('(function(){'. implode("\n", $code) . '}())'), ...$args);
    }

    public static function jsVar($name, $value, ...$directives)
    {
        return static::js(static::createJsVarCode($name, $value), ...$directives);
    }

    public static function jsCall($funcName, ...$args)
    {
        $funcArgs   = [];
        $directives = [];
        foreach($args as $arg)
        {
            if(isDirective($arg)) $directives[] = $arg;
            else $funcArgs[] = $arg;
        }
        $code = static::createJsCallCode($funcName, $funcArgs);
        return static::js($code, ...$directives);
    }

    public static function createJsCallCode($func, $args)
    {
        foreach($args as $index => $arg)
        {
            $args[$index] = h::encodeJsonWithRawJs($arg, JSON_UNESCAPED_UNICODE);
        }

        if($func[0] === '~')
        {
            $func = substr($func, 1);
            return "$(() => $func(" . implode(',', $args) . "));";
        }
        return $func . '(' . implode(',', $args) . ');';
    }

    public static function createJsVarCode($name, $value)
    {
        $vars = is_string($name) ? array($name => $value) : $name;
        $jsCode = '';
        foreach($vars as $var => $val)
        {
            if(empty($var)) continue;
            $val = h::encodeJsonWithRawJs($val);
            if(str_starts_with($var, 'window.')) $jsCode .= "$var=" . $val . ';';
            elseif(str_starts_with($var, '+')) $jsCode .= 'let ' . substr($var, 1) . '=' . $val . ';';
            else $jsCode .= "const $var=" . $val . ';';
        }
        return $jsCode;
    }

    public static function jsRaw()
    {
        return 'RAWJS<' . implode("\n", func_get_args()) . '>RAWJS';
    }

    protected static function encodeJsonWithRawJs($data)
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $json = str_replace('"RAWJS<', '', str_replace('>RAWJS"', '', $json));
        return $json;
    }

    protected static function splitRawCode($children)
    {
        $children = \zin\utils\flat($children);
        $code = [];
        $args = [];
        foreach($children as $key => $child)
        {
            if(is_string($child)) $code[] = $child;
            else $args[] = $child;
        }
        return [$code, $args];
    }

    public static $selfCloseTags = ['area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'];
}
