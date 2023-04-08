<?php
/**
 * The dom widget class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once 'selector.func.php';

class dom
{
    /**
     * @var wg
     */
    public $wg;

    public $children = [];

    public $selectors = NULL;

    public $renderInner = false;

    public $renderAsJson = NULL;

    /**
     * Construct the dom object.
     * @param  wg                  $wg
     * @param  array               $children
     * @param  array|string|object $selectors
     * @access public
     */
    public function __construct($wg, $children, $selectors = NULL)
    {
        $this->wg = $wg;
        $this->add($children);
        $this->addSelectors($selectors);
    }

    public function __debugInfo()
    {
        return
        [
            'gid'          => $this->wg->gid,
            'type'         => $this->wg->type(),
            'count'        => count($this->children),
            'renderInner'  => $this->renderInner,
            'renderAsJson' => $this->renderAsJson,
            'selectors'    => stringifyWgSelectors($this->selectors)
        ];
    }

    public function add($children)
    {
        if(empty($children)) return;

        if(!is_array($children)) $children = [$children];
        foreach($children as $child)
        {
            if(is_array($child)) $this->add($child);
            elseif(!empty($child)) $this->children[] = $child;
        }
    }

    public function addSelectors($selectors)
    {
        if(empty($selectors)) return;

        $selectors = parseWgSelectors($selectors);
        $this->selectors = $this->selectors === NULL ? $selectors : array_merge($this->selectors, $selectors);
    }

    public function isMatch($selector)
    {
        return $this->wg->isMatch($selector);
    }
    /**
     * Build the children dom list.
     * @access public
     * @return array
     */
    public function build()
    {
        $list  = [];
        $children = $this->renderInner ? $this->wg->children() : $this->children;

        if(empty($children)) return $list;

        foreach($children as $child)
        {
            $list[] = ($child instanceof wg) ? $child->buildDom() : $child;
        }

        if(!empty($this->selectors))
        {
            $list = static::filter($list, $this->selectors);
        }
        return $list;
    }

    public function render()
    {
        return $this->renderAsJson ? $this->renderJson() : $this->renderHtml();
    }

    public function renderJson()
    {
        $list = $this->build();
        if(empty($list)) return '';

        $output = [];
        foreach($list as $name => $item)
        {
            $output[$name] = static::renderItemToJson($item);
        }

        return json_encode($output);
    }

    public function renderHtml()
    {
        $list = $this->build();
        if(empty($list)) return '';

        $output = [];
        foreach($list as $item)
        {
            $output[] = static::renderItemToHtml($item);
        }
        return implode('', $output);
    }

    public static function renderItemToJson($item)
    {
        if($item === NULL || is_bool($item)) return NULL;

        if(is_array($item))
        {
            $output = [];
            foreach($item as $subItem) $output[] = static::renderItemToJson($subItem);
            return $output;
        }

        if($item instanceof dom) return $item->wg->toJsonData();
        if($item instanceof wg)  return $item->toJsonData();
        if(is_string($item))     return $item;

        if(is_object($item))
        {
            if(isDirective($item, 'html'))     return $item->data;
            if(isDirective($item, 'text'))     return htmlspecialchars($item->data);
            if(isset($item->html))             return $item->html;
            if(isset($item->text))             return htmlspecialchars($item->text);
            if(method_exists($item, 'render')) return $item->render();
        }

        return strval($item);
    }

    public static function renderItemToHtml($item)
    {
        if($item === NULL || is_bool($item)) return '';

        if(is_array($item))
        {
            $output = [];
            foreach($item as $subItem) $output[] = static::renderItemToHtml($subItem);
            return implode('', $output);
        }

        if($item instanceof dom) return dom::renderItemToHtml($item->build());
        if($item instanceof wg)  return $item->render();
        if(is_string($item))     return $item;

        if(is_object($item))
        {
            if(isDirective($item, 'html'))     return $item->data;
            if(isDirective($item, 'text'))     return htmlspecialchars($item->data);
            if(isset($item->html))             return $item->html;
            if(isset($item->text))             return htmlspecialchars($item->text);
            if(method_exists($item, 'render')) return $item->render();
        }

        return strval($item);
    }

    /**
     * Filter the dom list with selector.
     * @param  array  $list
     * @param  object $selector
     * @param  array  $filteredList
     * @access public
     * @return array
     */
    public static function filterList(&$list, $selector, &$filteredList)
    {
        if(empty($list) || empty($selector)) return [];

        $results = [];
        foreach($list as $item)
        {
            if(!($item instanceof dom) || in_array($item->wg->gid, $filteredList)) continue;

            if($item->wg->isMatch($selector))
            {
                $item->renderInner  = $selector->inner ?? false;
                $item->renderAsJson = $selector->json ?? false;
                $filteredList[]     = $item->wg->gid;
                $results[]          = $item;
            }
            else
            {
                $children = $item->build();
                if(!empty($children))
                {
                    $subResults = static::filterList($children, $selector, $filteredList);
                    foreach($subResults as $subItem) $results[] = $subItem;
                }
            }
            if($selector->first && !empty($results)) break;
        }
        return $results;
    }

    public static function filter(&$domList, $selectors)
    {
        if(empty($selectors)) return $domList;

        $list         = [];
        $filteredList = [];
        foreach($selectors as $selector)
        {
            $results = static::filterList($domList, $selector, $filteredList);
            if(!empty($results)) $list[$selector->name] = $results;
        }

        return $list;
    }
}
