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
    /**
     * The event name.
     * 事件名称。
     *
     * @access public
     * @var string
     */
    public string $event;

    /**
     * As a delegate event selector。
     * 作为委托事件选择器。
     *
     * @access public
     * @var array
     */
    public ?string $selector;

    /**
     * Whether is compatible mode.
     * 是否为兼容模式。
     *
     * @access public
     * @var bool
     */
    public bool $compatible = false;

    /**
     * The constructor.
     * 构造函数。
     *
     * @access public
     * @param string      $event    Event name.
     * @param string|null $selector As a delegate event selector.
     * @param array       $options  Event options.
     */
    public function __construct(string $event, ?string $selector = null, ?array $options = array())
    {
        parent::__construct('event', 'args');

        if(is_null($options)) $options = array();

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

    /**
     * Set to prevent event default behavior.
     * 设置阻止事件默认行为选项。
     *
     * @access public
     * @param  bool $prevent Whether prevent event default behavior.
     * @return self
     */
    public function prevent(bool $prevent = true): self
    {
        $this->options['prevent'] = $prevent;
        return $this;
    }

    /**
     * Set to stop event propagation.
     * 设置停止事件冒泡选项。
     *
     * @access public
     * @param  bool $stop Whether stop event propagation.
     * @return self
     */
    public function stop(bool $stop = true): self
    {
        $this->options['stop'] = $stop;
        return $this;
    }

    /**
     * Set to only trigger event on self element.
     * 设置只在自身元素上触发事件选项。
     *
     * @access public
     * @param  bool $self Whether only trigger event on self element.
     * @return self
     */
    public function self(bool $self = true): self
    {
        $this->options['self'] = $self;
        return $this;
    }

    /**
     * Convert to js code.
     * 转换为 js 代码。
     *
     * @access public
     * @param  string $joiner The joiner of each line.
     * @return string
     */
    public function toJS($joiner = "\n"): string
    {
        if($this->event === 'init') return parent::buildBody($joiner);

        $options    = $this->options;
        $bindMethod = (isset($options['once']) && $options['once']) ? 'once' : 'on';
        $selector   = $this->selector ? (',' . static::json($this->selector)) : '';

        $callback = parent::toJS($joiner);
        $events   = array_map(function($event){return $event . '.zin.on';}, explode('_', $this->event));
        $events   = implode(' ', $events);

        return "\$element.$bindMethod('$events'$selector,$callback);";
    }

    /**
     * Build body code.
     * 构建函数体代码。
     *
     * @access public
     * @param  string $joiner The joiner of each line.
     * @return string
     * @override
     */
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

    /**
     * Apply to widget.
     * 应用到部件。
     *
     * @access public
     * @param  wg     $wg        The widget instance.
     * @param  string $blockName The block name.
     * @return void
     * @override
     */
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

    /**
     * Bind the "change" event to widget.
     * 绑定 "change" 事件到部件。
     *
     * @access public
     * @param  null|string|jsCallback $selectorOrCallback
     * @param  null|array|string      $handlerOrOptions
     * @return on
     * @static
     */
    public static function change(null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null): on
    {
        return static::bind('change', $selectorOrCallback, $handlerOrOptions);
    }

    /**
     * Bind the "click" event to widget.
     * 绑定 "click" 事件到部件。
     *
     * @access public
     * @param  null|string|jsCallback $selectorOrCallback
     * @param  null|array|string      $handlerOrOptions
     * @return on
     * @static
     */
    public static function click(null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null): on
    {
        return static::bind('click', $selectorOrCallback, $handlerOrOptions);
    }

    /**
     * Bind the "dbclick" event to widget.
     * 绑定 "dbclick" 事件到部件。
     *
     * @access public
     * @param  null|string|jsCallback $selectorOrCallback
     * @param  null|array|string      $handlerOrOptions
     * @return on
     * @static
     */
    public static function dbclick(null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null): on
    {
        return static::bind('dbclick', $selectorOrCallback, $handlerOrOptions);
    }

    /**
     * Bind the "focus" event to widget.
     * 绑定 "focus" 事件到部件。
     *
     * @access public
     * @param  null|string|jsCallback $selectorOrCallback
     * @param  null|array|string      $handlerOrOptions
     * @return on
     * @static
     */
    public static function mouseenter(null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null): on
    {
        return static::bind('mouseenter', $selectorOrCallback, $handlerOrOptions);
    }

    /**
     * Bind the "mouseleave" event to widget.
     * 绑定 "mouseleave" 事件到部件。
     *
     * @access public
     * @param  null|string|jsCallback $selectorOrCallback
     * @param  null|array|string      $handlerOrOptions
     * @return on
     * @static
     */
    public static function mouseleave(null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null): on
    {
        return static::bind('mouseleave', $selectorOrCallback, $handlerOrOptions);
    }

    /**
     * Bind the "focus" event to widget.
     * 绑定 "focus" 事件到部件。
     *
     * @access public
     * @param  null|string|jsCallback $selectorOrCallback
     * @param  null|array|string      $handlerOrOptions
     * @return on
     * @static
     */
    public static function inited(null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null): on
    {
        return static::bind('inited', $selectorOrCallback, $handlerOrOptions);
    }

    /**
     * Bind the "focus" event to widget.
     * 绑定 "focus" 事件到部件。
     *
     * @access public
     * @param  null|string|jsCallback $selectorOrCallback
     * @param  null|array|string      $handlerOrOptions
     * @return on
     * @static
     */
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

    /**
     * Create on instance from callback.
     * 从回调创建 on 实例。
     *
     * @access public
     * @param  jsCallback $callback
     * @param  string     $event
     * @param  string     $selector
     * @param  array      $options
     * @return on
     * @static
     */
    public static function fromCallback(jsCallback $callback, string $event, ?string $selector = null, ?array $options = null): on
    {
        if(!($callback instanceof on))
        {
            $on = new on($event, $selector, $options);
            $on->appendCode($callback->buildBody());
            $on->args(...$callback->funcArgs);
            $on->name($callback->funcName);
            $callback->parent = $on;
            return $on;
        }

        $callback->event = $event;
        if(!is_null($selector)) $callback->selector = $selector;
        if(!is_null($options))  $callback->options = array_merge($callback->options, $options);
        return $callback;
    }

    /**
     * Bind event to widget with magic method.
     * 绑定事件到部件，使用魔术方法。
     *
     * @access public
     * @param  string $event
     * @param  array  $args
     * @return on
     * @static
     */
    public static function __callStatic(string $event, array $args)
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
 * @return on
 */
function on(string $event, string|null $selectorOrHandler = null, null|array|string $handlerOrOptions = null): on
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
