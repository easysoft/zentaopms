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
 */
class js extends directive
{
    protected array $jsLines = array();

    public function __construct(string|array|js|null ...$codes)
    {
        parent::__construct('js');
        $this->appendLines(...$codes);
    }

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

    public function call(string $func, mixed ...$args): self
    {
        $args = array_map(fn($arg) => static::json($arg), $args);
        return $this->appendLine($func, '(', implode(',', $args), ')');
    }

    public function do(string|array|js|null ...$codes): self
    {
        return $this->appendLines(...$codes);
    }

    public function let(string $name, mixed $value): self
    {
        return $this->appendLine('let', $name, '=', static::json($value));
    }

    public function set(string $name, mixed $value): self
    {
        return $this->appendLine('const', $name, '=', static::json($value));
    }

    public function globalVar(string $name, mixed $value): self
    {
        if(!str_starts_with($name, 'window.')) $name = 'window.' . $name;
        return $this->appendLine($name, '=', static::json($value));
    }

    public function with(string $name, string|array|js|null ...$codes): self
    {
        $this->appendLine('with(', $name, '){');
        $this->appendLines(...$codes);
        return $this->appendLine('}');
    }

    public function beginIf(string ...$conditions): self
    {
        $conditions = implode(' && ', $conditions);
        return $this->appendLine("if($conditions){");
    }

    public function elseIf(string ...$conditions): self
    {
        $conditions = implode(' && ', $conditions);
        return $this->appendLine("}else if($conditions){");
    }

    public function else(): self
    {
        return $this->appendLine('}else{');
    }

    public function endIf(): self
    {
        return $this->appendLine('}');
    }

    public function query(string $selector): jQuery
    {
        return new jQuery($selector);
    }

    public function scopeBegin(): self
    {
        return $this->appendLine(';(function(){');
    }

    public function scopeEnd(): self
    {
        return $this->appendLine('}());');
    }

    public function appendLine(string ...$codes): self
    {
        $line = trim(implode(' ', $codes));
        if(empty($line)) return $this;

        if(!str_ends_with(';', $line)) $line .= ';';
        $this->jsLines[] = $line;
        return $this;
    }

    public function appendLines(string|array|js|null ...$lines): self
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

    public function appendCode(string ...$codes): self
    {
        foreach($codes as $code)
        {
            if(empty($code)) continue;
            $this->jsLines[] = $code;
        }
        return $this;
    }

    public function toJS($joiner = "\n"): string
    {
        return implode($joiner, $this->jsLines);
    }

    public function toScopeJS(): string
    {
        return $this->scope($this->toJS());
    }

    public function applyToWg(wg &$wg, string $blockName): void
    {
        $wg->addToBlock($blockName, h::js($this->toJS()));
    }

    public static function scope(string ...$codes): string
    {
        return ';(function(){' . implode("\n", $codes) . '}());';
    }

    public static function json($data): string
    {
        return h::encodeJsonWithRawJs($data);
    }

    public static function context(mixed $value, null|string|bool $name = null, string|array|js|null ...$codes): jsContext
    {
        return new jsContext($value, $name, ...$codes);
    }

    public static function zui(?string $name = null, ?string $func = null, mixed ...$args): jsContext
    {
        if(is_null($name)) return static::context('zui');

        $context = static::context("zui.{$name}");
        if(is_null($func)) return $context;
        return $context->call($func, ...$args);
    }

    public static function jquery(string $selector, ?string $name = null, string|array|js|null ...$codes): jquery
    {
        return new jquery($selector, $name, ...$codes);
    }

    public static function __callStatic($name, $args)
    {
        $context = static::context("window.{$name}");
        if(empty($args)) return $context;
        return $context->call(...$args);
    }
}
