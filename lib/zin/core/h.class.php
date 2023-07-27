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

require_once dirname(__DIR__) . DS . 'utils' . DS . 'flat.func.php';
require_once __DIR__ . DS . 'wg.class.php';
require_once __DIR__ . DS . 'wg.func.php';

class h extends wg
{
    private static $selfCloseTags = array('area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr');

    protected static array $defineProps = array(
        'tagName: string',
        'selfClose?: bool'
    );

    public function getTagName(): string
    {
        return $this->props->get('tagName');
    }

    public function isDomElement(): bool
    {
        return true;
    }

    public function isSelfClose(): bool
    {
        $selfClose = $this->props->get('selfClose');
        if(is_bool($selfClose)) return $selfClose;

        return in_array($this->getTagName(), static::$selfCloseTags);
    }

    public function build(): array
    {
        if($this->isSelfClose()) return array($this->buildSelfCloseTag());

        return array($this->buildTagBegin(), parent::build(), $this->buildTagEnd());
    }

    public function toJSON(): array
    {
        $data = parent::toJSON();
        $data['type'] = 'h:' . $this->getTagName();
        return $data;
    }

    public function type(): string
    {
        return $this->getTagName();
    }

    public function shortType(): string
    {
        return $this->getTagName();
    }

    protected function getPropsStr(): string
    {
        $propStr = $this->props->toStr(array_keys(static::definedPropsList()));
        if($this->props->hasEvent() && empty($this->id()) && $this->getTagName() !== 'html') $propStr = "$propStr id='$this->gid'";
        return empty($propStr) ? '' : " $propStr";
    }

    protected function buildSelfCloseTag(): string
    {
        $tagName = $this->getTagName();
        $propStr = $this->getPropsStr();
        return "<$tagName$propStr />";
    }

    protected function buildTagBegin(): string
    {
        $tagName = $this->getTagName();
        $propStr = $this->getPropsStr();
        return "<$tagName$propStr>";
    }

    protected function buildTagEnd(): string
    {
        $tagName = $this->getTagName();
        return "</$tagName>";
    }

    public static function create(): h
    {
        $args = func_get_args();
        $tagName = array_shift($args);
        return new h(set('tagName', $tagName), $args);
    }

    public static function __callStatic(string $tagName, array $args): h
    {
        return new h(set('tagName', $tagName), $args);
    }

    public static function a(): h
    {
        $a = static::create('a', func_get_args());
        if($a->prop('target') === '_blank' && !$a->hasProp('rel')) $a->prop('rel', 'noopener noreferrer');
        return $a;
    }

    public static function button(): h
    {
        return static::create('button', set('type', 'button'), func_get_args());
    }

    public static function input(): h
    {
        return static::create('input', set('type', 'text'), func_get_args());
    }

    public static function formHidden(): h
    {
        $args = func_get_args();
        $name = array_shift($args);
        $value = array_shift($args);
        return static::create('input', set('type', 'hidden'), set::name($name), set::value($value), $args);
    }

    public static function checkbox(): h
    {
        return static::create('input', set('type', 'checkbox'), func_get_args());
    }

    public static function radio(): h
    {
        return static::create('input', set('type', 'radio'), func_get_args());
    }

    public static function date(): h
    {
        return static::create('input', set('type', 'date'), func_get_args());
    }

    public static function file(): h
    {
        return static::create('input', set('type', 'file'), func_get_args());
    }

    public static function textarea(): h
    {
        $args = func_get_args();
        list($code, $args) = h::splitRawCode($args);
        return call_user_func_array('static::create', array_merge(array('textarea', $code), $args));
    }

    /**
     * create a html comment tag <!--...-->
     *
     * @access public
     * @param  string $comment
     * @return directive
     */
    public static function comment(string $comment): directive
    {
        return html("<!-- $comment -->");
    }

    public static function importJs(): h
    {
        $args = func_get_args();
        $src = array_shift($args);
        return call_user_func_array('static::create', array_merge(array('script', set('src', $src)), $args));
    }

    public static function importCss(): h
    {
        $args = func_get_args();
        $src = array_shift($args);
        return call_user_func_array('static::create', array_merge(array('link', set('rel', 'stylesheet'), set('href', $src)), $args));
    }

    public static function import(): h|array|null
    {
        $args = func_get_args();
        $file = array_shift($args);
        $type = array_shift($args);
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
        if($type == 'js' || $type == 'cjs') return call_user_func_array('static::importJs', array_merge(array($file), $args));
        if($type == 'css') return call_user_func_array('static::importCss', array_merge(array($file), $args));
        return null;
    }

    public static function css(): ?h
    {
        $args = func_get_args();
        list($code, $args) = h::splitRawCode($args);
        if(empty($code)) return null;
        return call_user_func_array('static::create', array_merge(array('style', html(implode("\n", $code))), $args));
    }

    public static function globalJS(): ?h
    {
        $args = func_get_args();
        list($code, $args) = h::splitRawCode($args);
        if(empty($code)) return null;
        return call_user_func_array('static::create', array_merge(array('script', html(implode("\n", $code))), $args));
    }

    public static function js(): ?h
    {
        $args = func_get_args();
        list($code, $args) = h::splitRawCode($args);
        if(empty($code)) return null;
        return call_user_func_array('static::create', array_merge(array('script', html(h::createJsScopeCode($code))), $args));
    }

    public static function jsVar(): ?h
    {
        $args = func_get_args();
        $name = array_shift($args);
        $value = array_shift($args);
        return call_user_func_array('static::js', array_merge(array(static::createJsVarCode($name, $value)), $args));
    }

    public static function jsCall(): ?h
    {
        $args = func_get_args();
        $funcName = array_shift($args);
        $funcArgs   = array();
        $directives = array();
        foreach($args as $arg)
        {
            if(isDirective($arg)) $directives[] = $arg;
            else $funcArgs[] = $arg;
        }
        $code = static::createJsCallCode($funcName, $funcArgs);
        return call_user_func_array('static::js', array_merge(array($code), $directives));
    }

    public static function jsShare($name, $data): ?h
    {
        return h::js('$.share[' . json_encode($name) . ']=' . h::encodeJsonWithRawJs($data) . ';');
    }

    public static function createJsCallCode(string $func, array $args): string
    {
        foreach($args as $index => $arg)
        {
            $args[$index] = h::encodeJsonWithRawJs($arg);
        }

        $argsStr = implode(',', $args);
        if($func[0] === '~')
        {
            $func = substr($func, 1);
            return "$(() => $func($argsStr));";
        }
        return "$func($argsStr);";
    }

    public static function createJsVarCode(string|array $name, $value): string
    {
        $vars = is_string($name) ? array($name => $value) : $name;
        $jsCode = '';
        foreach($vars as $var => $val)
        {
            if(empty($var)) continue;

            $val = h::encodeJsonWithRawJs($val);

            if(str_starts_with($var, 'window.')) $jsCode .= "$var=$val;";
            elseif(str_starts_with($var, '+')) $jsCode .= 'let ' . substr($var, 1) . "=$val;";
            else $jsCode .= "const $var=$val;";
        }
        return $jsCode;
    }

    public static function createJsScopeCode(string|array $codes): string
    {
        if(is_array($codes)) $codes = implode("\n", $codes);
        return ";(function(){\n$codes\n}());";
    }

    public static function jsRaw(): string
    {
        return 'RAWJS<' . implode("\n", func_get_args()) . '>RAWJS';
    }

    protected static function encodeJsonWithRawJs(mixed $data): string
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        if(empty($json) && (is_array($data) || is_object($data))) return '[]';

        $json = str_replace('"RAWJS<', '', str_replace('>RAWJS"', '', $json));
        return $json;
    }

    protected static function splitRawCode(array $children): array
    {
        $children = \zin\utils\flat($children);
        $code = array();
        $args = array();
        foreach($children as $child)
        {
            if(is_string($child)) $code[] = $child;
            else $args[] = $child;
        }
        return array($code, $args);
    }
}
