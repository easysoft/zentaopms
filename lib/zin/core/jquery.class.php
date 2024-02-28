<?php
declare(strict_types=1);
/**
 * The jQuery context class file of zin lib.
 *
 * @copyright   Copyright 2024 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'jscontext.class.php';
require_once dirname(__DIR__) . DS . 'utils' . DS . 'classlist.class.php';

use zin\utils\classlist;

/**
 * Class for generating jQuery code with context.
 * 用于生成带上下文的 jQuery 代码的类。
 */
class jQuery extends jsContext
{
    /**
     * 构造函数。
     *
     * @access public
     * @param string                $selector The jQuery selector. jQuery 选择器。
     * @param string|null           $name     The jQuery name. jQuery 名称。
     * @param string|array|js|null ...$codes  The jQuery codes. jQuery 代码。
     */
    public function __construct(string $selector, ?string $name = null, string|array|js|null ...$codes)
    {
        parent::__construct(static::selector($selector), $name, ...$codes);
    }

    /**
     * Add class to the element.
     * 为元素添加类。
     *
     * @access public
     * @param mixed ...$classList The class list. 类列表。
     * @return self
     */
    public function addClass(mixed ...$classList): self
    {
        return $this->call('addClass', classlist::format(...$classList));
    }

    /**
     * Set class to the element.
     * 为元素设置类。
     *
     * @access public
     * @param mixed ...$classList The class list. 类列表。
     * @return self
     */
    public function setClass(mixed ...$classList): self
    {
        return $this->call('setClass', classlist::format(...$classList));
    }

    /**
     * Remove class from the element.
     * 为元素移除类。
     *
     * @access public
     * @param mixed ...$classList The class list. 类列表。
     * @return self
     */
    public function removeClass(mixed ...$classList): self
    {
        return $this->call('removeClass', classlist::format(...$classList));
    }

    /**
     * Toggle class to the element.
     * 为元素切换类。
     *
     * @access public
     * @param mixed  $classList The class list. 类列表。
     * @param string $condition The condition. 条件。
     * @return self
     */
    public function toggleClass(mixed $classList, string $condition): self
    {
        return $this->call('toggleClass', classlist::format($classList), $condition);
    }

    /**
     * Set css style to the element.
     * 为元素设置 CSS 样式。
     *
     * @access public
     * @param string|array|object $name  The css name. CSS 名称。
     * @param mixed               $value The css value. CSS 值。
     * @return self
     */
    public function css(string|array|object $name, mixed $value = null): self
    {
        if(is_string($name)) return $this->call('css', $name, $value);
        return $this->call('css', $name);
    }

    /**
     * Set HTML attribute to the element.
     * 为元素设置 HTML 属性。
     *
     * @access public
     * @param string|array|object $name  The attribute name. 属性名称。
     * @param mixed               $value The attribute value. 属性值。
     * @return self
     */
    public function attr(string|array|object $name, mixed $value = null): self
    {
        if(is_string($name)) return $this->call('attr', $name, $value);
        return $this->call('attr', $name);
    }

    /**
     * Remove attribute from the element.
     * 为元素移除 HTML 属性。
     *
     * @access public
     * @param string $name The attribute name. 属性名称。
     * @return self
     */
    public function removeAttr(string $name): self
    {
        return $this->call('removeAttr', $name);
    }

    /**
     * Set DOM property to the element.
     * 为元素设置 DOM 属性。
     *
     * @access public
     * @param string|array|object $name  The property name. 属性名称。
     * @param mixed               $value The property value. 属性值。
     * @return self
     */
    public function prop(string|array|object $name, mixed $value = null): self
    {
        if(is_string($name)) return $this->call('prop', $name, $value);
        return $this->call('prop', $name);
    }

    /**
     * Remove property from the element.
     * 为元素移除 DOM 属性。
     *
     * @access public
     * @param string $name The property name. 属性名称。
     * @return self
     */
    public function removeProp(string $name): self
    {
        return $this->call('removeProp', $name);
    }

    /**
     * Show element.
     * 将元素设置为可见。
     *
     * @access public
     * @return self
     */
    public function show(): self
    {
        return $this->call('show');
    }

    /**
     * Hide element.
     * 将元素设置为不可见。
     *
     * @access public
     * @return self
     */
    public function hide(): self
    {
        return $this->call('hide');
    }

    /**
     * Toggle element.
     * 切换元素的可见性。
     *
     * @access public
     * @param bool|null $toggle The toggle value. 切换值。
     * @return self
     */
    public function toggle(?bool $toggle = null): self
    {
        if(is_null($toggle)) return $this->call('toggle');
        return $this->call('toggle', $toggle);
    }

    /**
     * Trigger event on the element.
     * 在元素上触发事件。
     *
     * @access public
     * @param string $event The event name. 事件名称。
     * @param mixed  ...$args The event arguments. 事件参数。
     * @return self
     */
    public function trigger(string $event, mixed ...$args): self
    {
        return $this->call('trigger', $event, ...$args);
    }

    /**
     * Bind event on element.
     * 为元素绑定事件。
     *
     * @access public
     * @param string      $event            The event name. 事件名称。
     * @param string      $selectorOrHandler The selector or handler. 选择器或处理器。
     * @param string|null $handler           The handler. 处理器。
     * @return self
     */
    public function on(string $event, string|jsCallback $selectorOrHandler, null|string|jsCallback $handler = null): self
    {
        $selector = null;
        if(is_null($handler))   $handler = $selectorOrHandler;
        else                    $selector = $selectorOrHandler;

        if(is_string($handler)) $handler = jsRaw($handler);


        if(is_null($selector))
        {
            return $this->call('on', $event, $handler);
        }

        return $this->call('on', $event, $selector, $handler);
    }

    /**
     * Unbind event on element.
     * 为元素解绑事件。
     *
     * @access public
     * @param string $event The event name. 事件名称。
     * @return self
     */
    public function off(string $event): self
    {
        return $this->call('off', $event);
    }

    /**
     * Set element inner HTML.
     * 设置元素内部 HTML 内容。
     *
     * @access public
     * @param string $html The HTML content. HTML 内容。
     * @return self
     */
    public function html(string $html)
    {
        return $this->call('html', $html);
    }

    /**
     * Set element inner text.
     * 设置元素内部文本内容。
     *
     * @access public
     * @param string $text The text content. 文本内容。
     * @return self
     */
    public function text(string $text)
    {
        return $this->call('text', $text);
    }

    /**
     * Set element value.
     * 设置元素值。
     *
     * @access public
     * @param mixed $value The value. 值。
     * @return self
     */
    public function val(mixed $value)
    {
        return $this->call('val', $value);
    }

    /**
     * Empty element.
     * 清空元素。
     *
     * @access public
     * @return self
     */
    public function clear(): self
    {
        return $this->call('clear');
    }

    /**
     * Remove element from DOM.
     * 将元素从 DOM 中移除。
     *
     * @access public
     * @return self
     */
    public function remove(): self
    {
        return $this->call('remove');
    }

    /**
     * Call callback on each element.
     * 在每个元素上调用回调。
     *
     * @access public
     * @param string $callback The callback. 回调。
     * @return self
     */
    public function each(string|jsCallback $callback): self
    {
        if(is_string($callback)) $callback = jsRaw($callback);
        return $this->call('each', $callback);
    }

    /**
     * Insert element after the selector.
     * 将元素插入到选择器后面。
     *
     * @access public
     * @param string $selector The selector. 选择器。
     * @return self
     */
    public function insertAfter(string $selector): self
    {
        return $this->call('insertAfter', $selector);
    }

    /**
     * Insert element before the selector.
     * 将元素插入到选择器前面。
     *
     * @access public
     * @param string $selector The selector. 选择器。
     * @return self
     */
    public function insertBefore(string $selector): self
    {
        return $this->call('insertBefore', $selector);
    }

    /**
     * Insert content after the element.
     * 将内容插入到元素后面。
     *
     * @access public
     * @param string $selectorOrContent The selector or content. 选择器或内容。
     * @return self
     */
    public function after(string $selectorOrContent): self
    {
        return $this->call('after', $selectorOrContent);
    }

    /**
     * Insert content before the element.
     * 将内容插入到元素前面。
     *
     * @access public
     * @param string $selectorOrContent The selector or content. 选择器或内容。
     * @return self
     */
    public function before(string $selectorOrContent): self
    {
        return $this->call('before', $selectorOrContent);
    }

    /**
     * Append content to the element.
     * 将内容追加到元素。
     *
     * @access public
     * @param string $selectorOrContent The selector or content. 选择器或内容。
     * @return self
     */
    public function append(string $selectorOrContent): self
    {
        return $this->call('append', $selectorOrContent);
    }

    /**
     * Prepend content to the element.
     * 将内容前置到元素。
     *
     * @access public
     * @param string $selectorOrContent The selector or content. 选择器或内容。
     * @return self
     */
    public function prepend(string $selectorOrContent): self
    {
        return $this->call('prepend', $selectorOrContent);
    }

    /**
     * Append element to the selector.
     * 将元素追加到选择器。
     *
     * @access public
     * @param string $selector The selector. 选择器。
     * @return self
     */
    public function appendTo(string $selector): self
    {
        return $this->call('appendTo', $selector);
    }

    /**
     * Prepend element to the selector.
     * 将元素前置到选择器。
     *
     * @access public
     * @param string $selector The selector. 选择器。
     * @return self
     */
    public function prependTo(string $selector): self
    {
        return $this->call('prependTo', $selector);
    }

    /**
     * Replace element with content.
     * 用内容替换元素。
     *
     * @access public
     * $selectorOrContent The selector or content. 选择器或内容。
     * @return self
     */
    public function replaceWith(string $selectorOrContent): self
    {
        return $this->call('replaceWith', $selectorOrContent);
    }

    /**
     * Set width to the element.
     * 设置元素宽度。
     *
     * @access public
     * @param string|int $width The width. 宽度。
     * @return self
     */
    public function width(string|int $width): self
    {
        if(is_numeric($width)) $width .= 'px';
        return $this->call('width', $width);
    }

    /**
     * Set height to the element.
     * 设置元素高度。
     *
     * @access public
     * @param string|int $height The height. 高度。
     * @return self
     */
    public function height(string|int $height): self
    {
        if(is_numeric($height)) $height .= 'px';
        return $this->call('height', $height);
    }

    /**
     * Generate code for creating jQuery object with selector.
     * 根据选择器生成创建 jQuery 对象的代码。
     *
     * @access public
     * @param string $selector The selector. 选择器。
     * @return string
     * @static
     */
    public static function selector(string $selector): string
    {
        if(is_string($selector) && str_starts_with($selector, 'RAWJS<') && str_ends_with($selector, '>RAWJS')) return substr($selector, 6, -6);

        if(str_starts_with($selector, '$')) return $selector;

        if($selector !== 'window' && $selector !== 'document')
        {
            $selector = '\'' . str_replace('\'', "\'", $selector) . '\'';
        }
        return "\$($selector)";
    }
}

/**
 * Create a jQuery object.
 * 创建一个 jQuery 对象。
 *
 * @access public
 * @param string                $selector The jQuery selector. jQuery 选择器。
 * @param string|null           $name     The jQuery name. jQuery 名称。
 * @param string|array|js|null ...$codes  The jQuery codes. jQuery 代码。
 * @return jQuery
 */
function jQuery(string $selector, ?string $name = null, string|array|js|null ...$codes): jQuery
{
    return new jQuery($selector, $name, ...$codes);
}
