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

require_once __DIR__ . DS . 'wg.func.php';

use zin\jsContext;
use zin\jQuery;

/**
 * Class for generating js code.
 * 用于生成 JS 代码。
 *
 * @access public
 */
class js extends directive
{
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
        parent::__construct('js');
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
        $args = array_map(fn($arg) => static::json($arg), $args);
        return $this->appendLine($func, '(', implode(',', $args), ')');
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
        return $this->appendLine('let', $name, '=', static::json($value));
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
        return $this->appendLine('const', $name, '=', static::json($value));
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
        return $this->appendLine($name, '=', static::json($value));
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
                $line->parent = $this;
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
    public function toJS($joiner = "\n"): string
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
     * Apply JS code to zin widget.
     * 将 JS 代码应用到指定的 zin 部件中。
     *
     * @access public
     * @param wg     $wg        zin widget object.
     * @param string $blockName zin widget block name.
     */
    public function applyToWg(wg &$wg, string $blockName): void
    {
        $wg->addToBlock($blockName, h::js($this->toJS()));
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
     * Encode php value to JSON.
     * 将 PHP 值编码为 JSON。
     *
     * @access public
     * @param mixed $data PHP value.
     * @return string
     */
    public static function json($data): string
    {
        return h::encodeJsonWithRawJs($data);
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
     * Create window variables context object.
     * 创建 window 变量上下文操作辅助对象。
     *
     * @access public
     * @param null|string           $name     Name.
     * @param mixed                 ...$args  Arguments.
     * @return mixed
     */
    public static function __callStatic($name, $args): mixed
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
