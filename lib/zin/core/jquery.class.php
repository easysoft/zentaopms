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
 */
class jQuery extends jsContext
{
    public function __construct(string $selector, ?string $name = null, string|array|js|null ...$codes)
    {
        parent::__construct(static::select($selector), $name, ...$codes);
    }

    public function addClass(mixed ...$classList): self
    {
        return $this->call('addClass', classlist::format(...$classList));
    }

    public function setClass(mixed ...$classList): self
    {
        return $this->call('setClass', classlist::format(...$classList));
    }

    public function removeClass(mixed ...$classList): self
    {
        return $this->call('removeClass', classlist::format(...$classList));
    }

    public function toggleClass(mixed $classList, string $condition): self
    {
        return $this->call('toggleClass', classlist::format($classList), $condition);
    }

    public function css(string|array|object $name, mixed $value = null): self
    {
        if(is_string($name)) return $this->call('css', $name, $value);
        return $this->call('css', $name);
    }

    public function attr(string|array|object $name, mixed $value = null): self
    {
        if(is_string($name)) return $this->call('attr', $name, $value);
        return $this->call('attr', $name);
    }

    public function removeAttr(string $name): self
    {
        return $this->call('removeAttr', $name);
    }

    public function prop(string|array|object $name, mixed $value = null): self
    {
        if(is_string($name)) return $this->call('prop', $name, $value);
        return $this->call('prop', $name);
    }

    public function removeProp(string $name): self
    {
        return $this->call('removeProp', $name);
    }

    public function show(): self
    {
        return $this->call('show');
    }

    public function hide(): self
    {
        return $this->call('hide');
    }

    public function toggle(?bool $toggle = null): self
    {
        if(is_null($toggle)) return $this->call('toggle');
        return $this->call('toggle', $toggle);
    }

    public function trigger(string $event, mixed $data = null): self
    {
        return $this->call('trigger', $event, $data);
    }

    public function html($html)
    {
        return $this->call('html', $html);
    }

    public function text($text)
    {
        return $this->call('text', $text);
    }

    public function val($value)
    {
        return $this->call('val', $value);
    }

    public function on(string $event, string $selectorOrHandler, ?string $handler = null): self
    {
        if(is_null($handler))
        {
            return $this->call('on', $event, jsRaw($selectorOrHandler));
        }
        return $this->call('on', $event, $selectorOrHandler, jsRaw($handler));
    }

    public function off(string $event): self
    {
        return $this->call('off', $event);
    }

    public function remove(): self
    {
        return $this->call('remove');
    }

    public function empty(): self
    {
        return $this->call('empty');
    }

    public function each(string $callback): self
    {
        return $this->call('each', jsRaw($callback));
    }

    public function insertAfter(string $selector): self
    {
        return $this->call('insertAfter', $selector);
    }

    public function insertBefore(string $selector): self
    {
        return $this->call('insertBefore', $selector);
    }

    public function after(string $selector): self
    {
        return $this->call('after', $selector);
    }

    public function before(string $selector): self
    {
        return $this->call('before', $selector);
    }

    public function width(string $width): self
    {
        return $this->call('width', $width);
    }

    public function height(string $height): self
    {
        return $this->call('height', $height);
    }

    public function replaceWith(string $content): self
    {
        return $this->call('replaceWith', $content);
    }

    public function replaceAll(string $content): self
    {
        return $this->call('replaceAll', $content);
    }

    public function append(string $content): self
    {
        return $this->call('append', $content);
    }

    public function prepend(string $content): self
    {
        return $this->call('prepend', $content);
    }

    public function appendTo(string $content): self
    {
        return $this->call('appendTo', $content);
    }

    public function prependTo(string $content): self
    {
        return $this->call('prependTo', $content);
    }

    public static function select(string $selector)
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

function jQuery(string $selector, ?string $name = null, string|array|js|null ...$codes): jQuery
{
    return new jQuery($selector, $name, ...$codes);
}
