<?php
declare(strict_types=1);
/**
 * The events binding class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'wg.func.php';
require_once __DIR__ . DS . 'jscallback.class.php';

use zin\wg;

/**
 * Events binding class.
 */
class on extends jsCallback
{
    public ?string $selector;

    public bool $compatible = false;

    public string $event;

    public function __construct(string $event, ?string $selector = null, array $options = array())
    {
        parent::__construct('event', 'args');

        if(str_contains($event, '__'))
        {
            list($event, $flags) = explode('__', $event);
            if(str_contains($flags, 'stop'))    $options['stop']    = true;
            if(str_contains($flags, 'prevent')) $options['prevent'] = true;
            if(str_contains($flags, 'self'))    $options['self']    = true;
        }
        $this->options  = $options;
        $this->selector = $selector;
        $this->event    = $event;
    }

    public function prevent(bool $prevent = true): self
    {
        $this->options['prevent'] = $prevent;
        return $this;
    }

    public function stop(bool $stop = true): self
    {
        $this->options['stop'] = $stop;
        return $this;
    }

    public function self(bool $self = true): self
    {
        $this->options['self'] = $self;
        return $this;
    }

    public function capture(bool $capture = true): self
    {
        $this->options['capture'] = $capture;
        return $this;
    }

    public function toJS($joiner = "\n"): string
    {
        if($this->event === 'init') return parent::buildBody($joiner);

        $options    = $this->options;
        $bindMethod = (isset($options['once']) && $options['once']) ? 'once' : 'on';
        $selector   = $this->selector ? (',' . static::json($this->selector)) : '';

        $callback = parent::toJS($joiner);
        return "\$element.$bindMethod('{$this->event}.zin.on'$selector,$callback);";
    }

    public function buildBody(string $joiner = "\n"): string
    {
        $options = $this->options;
        $self    = isset($options['self'])    ? $options['self']    : false;
        $prevent = isset($options['prevent']) ? $options['prevent'] : false;
        $stop    = isset($options['stop'])    ? $options['stop']    : false;
        $arrow   = $this->isArrowFunc;
        $codes   = array();

        if(!$arrow)          $codes[] = "const \$this = $(this);";
                             $codes[] = "const target = event.target;";
        if($self && !$arrow) $codes[] = "if(target !== this) return;";
        if($prevent)         $codes[] = "event.preventDefault();";
        if($stop)            $codes[] = "event.stopPropagation();";
                             $codes[] = parent::buildBody($joiner);

        return implode($joiner, $codes);
    }

    public function applyToWg(wg &$wg, string $blockName): void
    {
        if($this->compatible)
        {
            $options = $this->options;
            $options['selector'] = $this->selector;
            $options['handler']  = parent::buildBody('');

            $wg->setProp("@{$this->event}", (object)$options);
            return;
        }

        $zuiInitCode = $wg->prop('zui-init', '');
        $wg->setProp('zui-init', $zuiInitCode . "\n" . $this->toJS());
    }

    public static function change(null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null): on
    {
        return static::bind('change', $selectorOrCallback, $handlerOrOptions);
    }

    public static function click(null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null): on
    {
        return static::bind('click', $selectorOrCallback, $handlerOrOptions);
    }

    public static function dbclick(null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null): on
    {
        return static::bind('dbclick', $selectorOrCallback, $handlerOrOptions);
    }

    public static function mouseenter(null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null): on
    {
        return static::bind('mouseenter', $selectorOrCallback, $handlerOrOptions);
    }

    public static function mouseleave(null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null): on
    {
        return static::bind('mouseleave', $selectorOrCallback, $handlerOrOptions);
    }

    public static function inited(null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null): on
    {
        return static::bind('inited', $selectorOrCallback, $handlerOrOptions);
    }

    public static function bind(string $event, null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null): on
    {
        if($selectorOrCallback instanceof jsCallback)
        {
            return static::fromCallback($selectorOrCallback, $event, null, $handlerOrOptions);
        }
        if($handlerOrOptions instanceof jsCallback)
        {
            return static::fromCallback($handlerOrOptions, $event, $selectorOrCallback);
        }
        return on($event, $selectorOrCallback, $handlerOrOptions);
    }

    public static function fromCallback(jsCallback $callback, string $event, ?string $selector = null, ?array $options = null): on
    {
        if(!($callback instanceof on))
        {
            $on = new on($event, $selector, $options);
            $on->appendCode($callback->buildBody());
            $on->args(...$callback->funcArgs);
            $on->name($callback->funcName);
            return $on;
        }

        $callback->event = $event;
        if(!is_null($selector)) $callback->selector = $selector;
        if(!is_null($options))  $callback->options = array_merge($callback->options, $options);
        return $callback;
    }

    public static function __callStatic($event, $args)
    {
        list($selectorOrCallback, $options) = array_merge($args, array(null, null));
        return static::bind($event, $selectorOrCallback, $options);
    }
}

/**
 * Add event listener to widget element.
 *
 * @param  string             $event
 * @param  null|string        $selectorOrHandler
 * @param  null|array|string  $handlerOrOptions
 */
function on(string $event, string|null $selectorOrHandler = null, null|array|string $handlerOrOptions = null): directive
{
    $options  = array();
    $selector = null;
    $handler  = null;

    if(is_string($selectorOrHandler))
    {
        if(is_string($handlerOrOptions))
        {
            $selector = $selectorOrHandler;
            $handler  = $handlerOrOptions;
        }
        else
        {
            if(str_contains('.#[', $selectorOrHandler[0])) $selector = $selectorOrHandler;
            else                                           $handler  = $selectorOrHandler;
        }
    }
    if(is_array($handlerOrOptions)) $options = array_merge($options, $handlerOrOptions);

    if(str_contains($event, '__'))
    {
        list($event, $flags) = explode('__', $event);
        if(str_contains($flags, 'stop'))    $options['stop']    = true;
        if(str_contains($flags, 'prevent')) $options['prevent'] = true;
        if(str_contains($flags, 'self'))    $options['self']    = true;
    }

    $on = new on($event, $selector, $options);
    if(!is_null($handler))
    {
        $on->compatible = true;
        $on->appendCode($handler);
    }

    return $on;
}
