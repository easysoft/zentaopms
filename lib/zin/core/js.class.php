<?php
declare(strict_types=1);
/**
 * The js class file of zin lib.
 *
 * @copyright   Copyright 2024 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'json.func.php';
require_once __DIR__ . DS . 'node.class.php';
require_once __DIR__ . DS . 'directive.class.php';
require_once __DIR__ . DS . 'zin.func.php';

use zin\jsContext;
use zin\jsCallback;
use zin\jQuery;
use zin\node;

/**
 * Class for generating js code.
 * 用于生成 JS 代码。
 *
 * @access public
 */
class js implements \JsonSerializable, iDirective
{
    public bool $notRenderInGlobal = true;

    /**
     * The js code lines.
     * JS 代码行。
     *
     * @access protected
     * @var array
     */
    protected array $jsLines = array();

    /**
     * The construct function.
     * 构造函数。
     *
     * @param null|string|js|array ...$codes Codes.
     */
    public function __construct(null|string|js|array ...$codes)
    {
        $this->appendLines(...$codes);
    }

    /**
     * The call magic function, used to call other methods on the object by condition through the xxxIf method.
     * 魔术方法，用于通过 xxxIf 的方式根据条件调用对象上的其他方法。
     *
     * @access public
     * @param string $name      Method name.
     * @param array  $arguments Arguments.
     * @return self
     */
    public function __call($name, $arguments)
    {
        if(str_ends_with($name, 'If'))
        {
            $methodName = substr($name, 0, -2);
            if(method_exists($this, $methodName))
            {
                $condition = array_shift($arguments);
                $this->beginIf($condition);
                $this->$methodName(...$arguments);
                return $this->endIf();
            }
        }
        trigger_error("Call to undefined method " . __CLASS__ . "::{$name}()", E_USER_ERROR);
    }

    /**
     * The magic function, used to convert the object to a string.
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->toJS();
    }

    public function __debugInfo(): array
    {
        return array(
            'jsLines' => $this->jsLines
        );
    }

    /**
     * Call a function.
     * 调用一个函数。
     *
     * @access public
     * @param string $func    Function name.
     * @param mixed  ...$args Arguments.
     * @return self
     */
    public function call(string $func, mixed ...$args): self
    {
        $argCodes = array();
        foreach($args as $arg)
        {
            $argCodes[] = ($arg instanceof js) ? $arg->toJS() : static::value($arg);
        }

        return $this->appendLine($func . '(' . implode(',', $argCodes) . ')');
    }

    /**
     * Append JS codes.
     * 追加要执行的 JS 代码。
     *
     * @access public
     * @param mixed ...$codes Codes.
     * @return self
     */
    public function do(null|string|js|array ...$codes): self
    {
        return $this->appendLines(...$codes);
    }

    /**
     * Declare JS variables.
     * 声明 JS 变量。
     *
     * @access public
     * @param string $name  Variable name.
     * @param mixed  $value Variable value.
     * @return self
     */
    public function let(string $name, mixed $value): self
    {
        return $this->appendLine('let', $name, '=', static::value($value));
    }

    /**
     * Declare JS constants.
     * 声明 JS 常量。
     *
     * @access public
     * @param string $name  Constant name.
     * @param mixed  $value Constant value.
     * @return self
     */
    public function const(string $name, mixed $value): self
    {
        return $this->appendLine('const', $name, '=', static::value($value));
    }

    /**
     * Declare JS variables globally.
     * 声明 JS 全局变量。
     *
     * @access public
     * @param string $name  Variable name.
     * @param mixed  $value Variable value.
     * @return self
     */
    public function globalVar(string $name, mixed $value): self
    {
        if(!str_starts_with($name, 'window.')) $name = 'window.' . $name;
        return $this->appendLine($name, '=', static::value($value));
    }

    public function var(string|array $nameOrVars, mixed $value = null): self
    {
        if(is_array($nameOrVars))
        {
            foreach($nameOrVars as $name => $val) $this->var($name, $val);
            return $this;
        }

        $name = $nameOrVars;
        if(str_starts_with($name, '+'))       return $this->let(substr($name, 1), $value);
        if(str_starts_with($name, 'window.')) return $this->globalVar($name, $value);
        return $this->const($name, $value);
    }

    /**
     * Declare JS scope with keyword "with".
     * 声明 JS 作用域，使用 with 关键字。
     *
     * @access public
     * @param string $name  Variable name.
     * @param mixed  $codes Scoped codes.
     * @return self
     */
    public function with(string $name, null|string|js|array ...$codes): self
    {
        $this->appendLine('with(', $name, '){');
        $this->appendLines(...$codes);
        return $this->appendLine('}');
    }

    /**
     * Begin declaring "if" statements.
     * 开始声明 "if" 语句。
     *
     * @access public
     * @param mixed  $conditions Conditions.
     * @return self
     */
    public function beginIf(string ...$conditions): self
    {
        $conditions = implode(' && ', $conditions);
        return $this->appendLine("if($conditions){");
    }

    /**
     * Declare "else if" statements.
     * 声明 "else if" 语句。
     *
     * @access public
     * @param mixed  $conditions Conditions.
     * @return self
     */
    public function elseIf(string ...$conditions): self
    {
        $conditions = implode(' && ', $conditions);
        return $this->appendLine("}else if($conditions){");
    }

    /**
     * Declare "else" statements.
     * 声明 "else" 语句。
     *
     * @access public
     * @return self
     */
    public function else(): self
    {
        return $this->appendLine('}else{');
    }

    /**
     * Declare "if" statements end.
     * 声明 "if" 结束的括号。
     *
     * @access public
     * @return self
     */
    public function endIf(): self
    {
        return $this->appendLine('}');
    }

    /**
     * Declare independent scopes with IIFE.
     * 使用立即执行函数声明独立的作用域。
     *
     * @access public
     * @return self
     */
    public function scopeBegin(): self
    {
        return $this->appendLine(';(function(){');
    }

    /**
     * Declare independent scopes end.
     * 声明独立的作用域的结束部分。
     *
     * @access public
     * @return self
     */
    public function scopeEnd(): self
    {
        return $this->appendLine('}());');
    }

    /**
     * Append a line of JS code.
     * 追加一行 JS 代码。
     *
     * @access public
     * @param string ...$codes Codes.
     * @return self
     */
    public function appendLine(string ...$codes): self
    {
        $line = trim(implode(' ', $codes));
        if(empty($line)) return $this;

        if(!str_ends_with(';', $line)) $line .= ';';
        $this->jsLines[] = $line;
        return $this;
    }

    /**
     * Append lines of JS code.
     * 追加多行 JS 代码。
     *
     * @access public
     * @param null|string|js|array ...$lines Lines.
     * @return self
     */
    public function appendLines(null|string|js|array ...$lines): self
    {
        foreach($lines as $line)
        {
            if(is_null($line)) continue;
            if(is_array($line))
            {
                $this->appendLines(...$line);
                continue;
            }
            if($line instanceof js)
            {
                $line = $line->toJS();
            }
            $this->appendLine($line);
        }
        return $this;
    }

    /**
     * Append JS codes.
     * 追加要执行的 JS 代码。
     *
     * @access public
     * @param string ...$codes Codes.
     * @return self
     */
    public function appendCode(string ...$codes): self
    {
        foreach($codes as $code)
        {
            if(empty($code)) continue;
            $this->jsLines[] = $code;
        }
        return $this;
    }

    /**
     * Convert js code to string.
     * 将 JS 导出为代码字符串。
     *
     * @access public
     * @param string $joiner Joiner.
     * @return string
     */
    public function toJS(string $joiner = "\n"): string
    {
        return implode($joiner, $this->jsLines);
    }

    /**
     * Convert js code to string with IIFE scope.
     * 将 JS 导出为代码字符串，并使用立即执行函数作用域。
     *
     * @access public
     * @param string $joiner Joiner.
     * @return string
     */
    public function toScopeJS(string $joiner = "\n"): string
    {
        return $this->scope($this->toJS($joiner));
    }

    /**
     * Apply JS code to zin node.
     * 将 JS 代码应用到指定的 zin 部件中。
     *
     * @access public
     * @param node     $node        zin node object.
     * @param string   $blockName   zin node block name.
     */
    public function apply(node $node, string $blockName): void
    {
        $node->addToBlock($blockName, h::js($this->toJS()));
    }

    /**
     * Serialized to JSON.
     * 序列化为 JSON。
     *
     * @access public
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        $js = trim($this->toJS());
        if(str_ends_with(';', $js)) $js = substr($js, 0, -1);
        return js::raw($js);
    }

    public static function raw(string ...$codes)
    {
        $js = implode('<RAWJS_LINE>', $codes);
        $js = str_replace(array("\n", '"'), array('<RAWJS_LINE>', '<RAWJS_QUOTE>'), $js);
        return "RAWJS<$js>RAWJS";
    }

    public static function decodeRaw(string $str): string
    {
        if(!str_contains($str, 'RAWJS')) return $str;
        return str_replace(array('<RAWJS_LINE>', '<RAWJS_QUOTE>', '"RAWJS<', '>RAWJS"'), array("\n", '"', '', ''), $str);
    }

    /**
     * Wrap js code with IIFE scope.
     * 使用立即执行函数作用域包装 JS 代码。
     *
     * @access public
     * @param string ...$codes Codes.
     * @return string
     */
    public static function scope(null|string|js|array ...$codes): string
    {
        $js = new js(...$codes);
        return ';(function(){' . $js->toJS() . '}());';
    }

    /**
     * Encode php value to JS code.
     * 将 PHP 值编码为 JS 代码。
     *
     * @access public
     * @param mixed $data PHP value.
     * @return string
     */
    public static function value(mixed $data): string
    {
        if($data instanceof js) return $data->toJS();
        $js = \zin\utils\jsonEncode($data, JSON_UNESCAPED_UNICODE);
        if(empty($js) && (is_array($data) || is_object($data))) return '[]';

        return static::decodeRaw($js);
    }

    /**
     * Create js var definition.
     * 创建 JS 变量定义。
     *
     * @access public
     * @param string $name  Variable name.
     * @param mixed  $value Variable value.
     * @return string
     */
    public static function defineVar(string $name, mixed $value): string
    {
        $js = new js();
        $js->var($name, $value);
        return $js->toJS();
    }

    public static function defineJSCall(string $func, array $args): string
    {
        $js = new js();
        $js->call($func, ...$args);
        return $js->toJS();
    }

    /**
     * Create js context object.
     * 创建给定 JS 值的上下文操作辅助对象。
     *
     * @access public
     * @param mixed                 $value Value.
     * @param null|string|bool      $name  Name.
     * @param null|string|js|array  ...$codes Codes.
     * @return jsContext
     */
    public static function context(mixed $value, null|string|bool $name = null, null|string|js|array ...$codes): jsContext
    {
        return new jsContext($value, $name, ...$codes);
    }

    /**
     * Create zui context object.
     * 创建 ZUI 上下文操作辅助对象。
     *
     * @access public
     * @param null|string           $name     Name.
     * @return jsContext
     */
    public static function zui(?string $name = null): jsContext
    {
        if(is_null($name)) return static::context('zui');

        return static::context("zui.{$name}");
    }

    /**
     * Create jquery context object.
     * 创建 jQuery 上下文操作辅助对象。
     *
     * @access public
     * @param string                $selector Selector.
     * @param null|string           $name     Name.
     * @param null|string|js|array  ...$codes Codes.
     * @return jquery
     */
    public static function jquery(string $selector, ?string $name = null, null|string|js|array ...$codes): jquery
    {
        return new jquery($selector, $name, ...$codes);
    }

    /**
     * Create js callback object.
     * 创建 js 回调函数代码生成对象。
     *
     * @access public
     * @param string  ...$args Function argument name list.
     * @return jsCallback
     * @static
     */
    public static function callback(string ...$args): jsCallback
    {
        return new jsCallback(...$args);
    }

    /**
     * Create window variables context object.
     * 创建 window 变量上下文操作辅助对象。
     *
     * @access public
     * @param string           $name     Name.
     * @param mixed            ...$args  Arguments.
     */
    public static function __callStatic(string $name, array $args)
    {
        $context = static::context("window.{$name}");
        if(empty($args)) return $context;
        return $context->call(...$args);
    }
}

/**
 * Create js object.
 * 创建 JS 对象。
 *
 * @access public
 * @param null|string|js|array ...$codes Codes.
 * @return js
 */
function js(null|string|js|array ...$codes): js
{
    return new js(...$codes);
}

function jsRaw(string ...$codes): string
{
    return js::raw(...$codes);
}
