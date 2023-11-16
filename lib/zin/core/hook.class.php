<?php
declare(strict_types=1);
/**
 * The hook class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'wg.class.php';
require_once __DIR__ . DS . 'selector.func.php';

/**
 * The hook class.
 */
class hook
{
    /** The global root widget. */
    public static wg $globalRoot;

    /** The root widget. */
    public wg $root;

    /** The selectors object. */
    public object|null $selectors;

    /**
     * The items list.
     *
     * @var wg[]
     */
    public array $items;

    /**
     * Construct the select object.
     *
     * @param  array|string|wg     $selector
     * @param  wg|null             $root
     * @access public
     */
    public function __construct(array|string|wg $selector = '', wg|null $root = null)
    {
        $this->root      = empty($root) ? static::$globalRoot : $root;
        $this->selectors = null;
        $this->items     = array();

        if(is_string($selector))
        {
            $this->selectors = parseWgSelector($selector);
            $this->items     = $this->root->find($this->selectors);
        }
        elseif(is_object($selector))
        {
            $this->items = array($selector);
        }
        elseif(is_array($selector))
        {
            $this->items = $selector;
        }
    }

    /**
     * Get item by index.
     *
     * @param  int $index
     * @access public
     * @return wg|null
     */
    public function get(int $index = 0): wg | null
    {
        return isset($this->items[$index]) ? $this->items[$index] : null;
    }

    /**
     * Select items by selector.
     *
     * @param  string $selector
     * @access public
     * @return array
     */
    public function select(string $selector): array
    {
        $list = array();
        foreach($this->items as $item) $list = array_merge($list, $item->find($selector));
        return $list;
    }

    /**
     * Get items count.
     *
     * @access public
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Find items in widget.
     *
     * @access public
     * @return hook
     */
    public function find(string $selector): hook
    {
        if(empty($selector)) return $this;

        return new select($this->select($selector), $this->root);
    }

    /**
     * Get the first item.
     *
     * @param  string $selector
     * @access public
     * @return hook
     */
    public function first(string $selector = ''): hook
    {
        if(empty($selector)) return new hook(reset($this->items), $this->root);
        return $this->find($selector)->first();
    }

    /**
     * Get the last item.
     *
     * @param  string $selector
     * @access public
     * @return hook
     */
    public function last(string $selector = ''): hook
    {
        if(empty($selector)) return new hook(end($this->items), $this->root);
        return $this->find($selector)->last();
    }

    /**
     * Check if the element is match any of the selectors.
     *
     * @param  string|array|object $selectors
     * @access public
     * @return bool
     */
    public function is(string|array|object $selectors): bool
    {
        $selectors = parseWgSelectors($selectors);
        foreach($this->items as $item)
        {
            if($item->isMatch($selectors)) return true;
        }
        return false;
    }

    /**
     * Mark widget been removed.
     *
     * @param string $selector
     * @access public
     * @return hook
     */
    public function remove(string $selector = ''): hook
    {
        if(empty($selector))
        {
            foreach($this->items as $item) $item->removed = true;
        }
        else
        {
            $this->find($selector)->remove();
        }
        return $this;
    }

    /**
     * Append contents to parent.
     *
     * @param  wg|array|string ...$item
     * @access public
     * @return hook
     */
    public function append(/* wg|array|string ...$item */): hook
    {
        $newItems = func_get_args();
        foreach($this->items as $item) $item->add($newItems);
        return $this;
    }

    /**
     * Set widget classNames.
     *
     * @param  wg|array|string ...$item
     * @access public
     * @return hook
     */
    public function setClass(/* string|array ...$className */): hook
    {
        return $this->append(setClass(func_get_args()));
    }

    /**
     * Set widget style.
     *
     * @param  string|array $name
     * @param  string|null  $value
     * @access public
     */
    public function setStyle(array|string $name, ?string $value = null): hook
    {
        return $this->append(setStyle($name, $value));
    }

    /**
     * Set widget property.
     *
     * @param  string|array|props|null $name
     * @param  mixed                   $value
     * @access public
     */
    public function setProp(props|array|string $prop, mixed $value = null): hook
    {
        foreach($this->items as $item) $item->setProp($prop, $value);
        return $this;
    }

    /**
     * Remove prop from widget.
     *
     * @param  string $prop
     * @access public
     * @return hook
     */
    public function removeProp(string $prop): hook
    {
        foreach($this->items as $item) $item->props->remove($prop);
        return $this;
    }

    /**
     * Debug info.
     *
     * @access public
     * @return array
     */
    public function __debugInfo(): array
    {
        return array(
            'gid'       => $this->root->gid,
            'type'      => $this->root->type(),
            'count'     => $this->count(),
            'selectors' => $this->selectors,
            'items'     => $this->items
        );
    }
}

/**
 * Create a hook object.
 *
 * @param  array|string|object $selectors
 * @param  wg|null             $root
 */
function hook($selectors = '', $root = null): hook
{
    return new hook($selectors, $root);
}
