<?php
declare(strict_types=1);
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

use stdClass;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'deep.func.php';
require_once __DIR__ . DS . 'selector.func.php';

class dom
{
    /**
     * @var wg
     */
    public $wg;

    public $children = array();

    public $selectors = null;

    public $renderInner = false;

    public $renderType;

    public $dataGetters = null;

    public $dataCommands;

    public $buildList = null;

    public $buildListInner = false;

    /**
     * Construct the dom object.
     *
     * @param  wg                  $wg
     * @param  array               $children
     * @param  array|string|object $selectors
     * @access public
     */
    public function __construct($wg, $children, $selectors = null, $renderType = null, $dataCommands = null)
    {
        $this->wg           = $wg;
        $this->renderType   = $renderType;

        $this->add($children);
        $this->addSelectors($selectors);
        $this->addDataCommands($dataCommands);
    }

    public function __debugInfo()
    {
        return array(
            'gid'          => $this->wg->gid,
            'type'         => $this->wg->type(),
            'count'        => count($this->children),
            'renderInner'  => $this->renderInner,
            'renderType'   => $this->renderType,
            'dataCommands' => $this->dataCommands,
            'selectors'    => stringifyWgSelectors($this->selectors)
        );
    }

    public function add($children)
    {
        if(empty($children)) return;

        if(!is_array($children)) $children = [$children];
        foreach($children as $child)
        {
            if(is_array($child)) $this->add($child);
            else $this->children[] = $child;
        }
    }

    public function addDataCommands($commands)
    {
        if(empty($commands)) return;

        if(is_string($commands))
        {
            $commandList = explode(',', $commands);
            $commands    = array();
            foreach($commandList as $command)
            {
                $parts = explode(':', $command, 2);
                $commands[$parts[0]] = count($parts) > 1 ? $parts[1] : $parts[0];
            }
        }

        if($this->dataCommands === null) $this->dataCommands = array();
        $index = 0;
        foreach($commands as $key => $command)
        {
            $this->dataCommands[$index == $key ? $command : $key] = $command;
            $index++;
        }
    }

    public function addSelectors($selectors)
    {
        if(empty($selectors)) return;

        if($this->selectors === null) $this->selectors = array();
        $selectors = parseWgSelectors($selectors);
        foreach($selectors as $selector)
        {
            if(isset($selector->command) && !empty($selector->command)) $this->addDataCommands([$selector->tag => $selector->command]);
            else $this->selectors[] = $selector;
        }
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
        if($this->wg->removed) return array();

        if($this->buildList !== null && $this->buildListInner === $this->renderInner) return $this->buildList;

        if(empty($this->selectors) && !empty($this->dataCommands))
        {
            $this->buildList = array();
            return $this->buildList;
        }

        $list     = array();
        $children = $this->renderInner ? $this->wg->children() : $this->children;

        if(empty($children)) return $list;

        foreach($children as $child) $list[] = ($child instanceof wg) ? $child->buildDom() : $child;

        if(!empty($this->selectors)) $list = static::filter($list, $this->selectors);

        $this->buildList      = $list;
        $this->buildListInner = $this->renderInner;
        return $list;
    }

    public function render()
    {
        if($this->renderType === 'json') return $this->renderJson();
        if($this->renderType === 'list') return $this->renderList();
        return $this->renderHtml();
    }

    /**
     * Render dom to json object.
     *
     * @access public
     * @return object
     */
    public function renderJson(): object
    {
        $output = new stdClass();
        if($this->wg->removed) return $output;

        $list   = $this->build();
        foreach($list as $name => $item)
        {
            $output->$name = static::renderItemToJson($item);
        }

        if(!empty($this->dataCommands))
        {
            $data = array();
            foreach($this->dataCommands as $name => $command)
            {
                $data[$name] = data($command);
            }
            $output->data = $data;
        }

        return $output;
    }

    /**
     * Render dom to html string.
     *
     * @access public
     * @return string
     */
    public function renderHtml(): string
    {
        if($this->wg->removed) return '';

        $list = $this->build();
        if(empty($list)) return '';

        $output = array();
        foreach($list as $item)
        {
            $result = static::renderItemToHtml($item);
            if(!is_string($result)) $result = json_encode($result);
            $output[] = $result;
        }
        return implode('', $output);
    }

    /**
     * Render dom to list by given selector.
     *
     * @access public
     * @return array
     */
    public function renderList(): array
    {
        $output = array();
        if($this->wg->removed) return $output;

        $list   = $this->build();
        foreach($list as $name => $item)
        {
            if(is_array($item) && count($item) === 1) $item = $item[0];
            $renderType = $item instanceof dom ? $item->renderType : 'html';
            if(empty($renderType)) $renderType = 'html';
            $output[] = array('name' => $name, 'data' => static::renderDomItem($item, $renderType), 'type' => $renderType);
        }

        if(!empty($this->dataCommands))
        {
            foreach($this->dataCommands as $name => $command)
            {
                $output[] = array('name' => $name, 'data' => data($command), 'type' => 'command');
            }
        }

        return $output;
    }

    public static function renderDomItem($item, $defaultType = 'html')
    {
        if($item instanceof dom)
        {
            $renderType = $item->renderType;
            if(empty($renderType)) $renderType = $defaultType;
            if($renderType === 'json') return dom::renderItemToJson($item);
            return dom::renderItemToHtml($item->build());
        }

        $renderType = $defaultType;
        if($renderType === 'json') return static::renderItemToJson($item);
        return static::renderItemToHtml($item);
    }

    public static function renderItemToJson($item)
    {
        if($item === null || is_bool($item)) return null;

        if(is_array($item))
        {
            $output = array();
            foreach($item as $subItem) $output[] = static::renderItemToJson($subItem);
            return $output;
        }

        if($item instanceof dom)
        {
            $json = $item->wg->toJSON();
            if(!empty($item->dataGetters))
            {
                $output = array();
                $props  = explode(',', $item->dataGetters);
                foreach($props as $prop)
                {
                    $prop = trim($prop);
                    if(empty($prop)) continue;

                    $parts    = explode(':', $prop, 2);
                    $name     = $parts[0];
                    $namePath = count($parts) > 1 ? $parts[1] : $parts[0];
                    $output[$name] = \zin\utils\deepGet($json, $namePath);
                }
                return $output;
            }
            return $json;
        }
        if($item instanceof wg)  return dom::renderDomItem($item, 'json');
        if(is_string($item))     return $item;

        if(is_object($item))
        {
            if(isDirective($item, 'html'))     return $item->data;
            if(isDirective($item, 'text'))     return htmlspecialchars($item->data, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, null, false);
            if(isset($item->html))             return $item->html;
            if(isset($item->text))             return htmlspecialchars($item->text, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, null, false);
            if(method_exists($item, 'render')) return $item->render();
        }

        return strval($item);
    }

    public static function renderItemToHtml($item)
    {
        if($item === null || is_bool($item)) return '';

        if(is_array($item))
        {
            $output = array();
            foreach($item as $subItem) $output[] = static::renderItemToHtml($subItem);
            return implode('', $output);
        }

        if($item instanceof dom) return dom::renderItemToHtml($item->build());
        if($item instanceof wg)  return $item->render();
        if(is_string($item))     return $item;

        if(is_object($item))
        {
            if(isDirective($item, 'html'))     return $item->data;
            if(isDirective($item, 'text'))     return htmlspecialchars($item->data, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, null, false);
            if(isset($item->html))             return $item->html;
            if(isset($item->text))             return htmlspecialchars($item->text, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, null, false);
            if(method_exists($item, 'render')) return $item->render();
        }

        return strval($item);
    }

    /**
     * Filter the dom list with selector.
     *
     * @param  array  $list
     * @param  object $selector
     * @param  array  $filteredList
     * @access public
     * @return array
     */
    public static function filterList(&$list, $selector, &$filteredList)
    {
        if(empty($list) || empty($selector)) return [];

        $results = array();
        foreach($list as $item)
        {
            if(!($item instanceof dom) || $item->wg->removed || in_array($item->wg->gid, $filteredList)) continue;

            if($item->wg->isMatch($selector))
            {
                $item->selector    = $selector;
                $item->renderInner = isset($selector->inner) ? $selector->inner : false;
                $item->renderType  = isset($selector->type) ? $selector->type : null;
                $item->dataGetters = isset($selector->data) ? $selector->data : null;

                $filteredList[]    = $item->wg->gid;
                $results[]         = $item;
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

    /**
     * Filter the dom list with selectors.
     *
     * @param  array  $domList
     * @param  array  $selectors
     * @access public
     * @return array
     */
    public static function filter(&$domList, $selectors)
    {
        if(empty($selectors)) return $domList;

        $list         = array();
        $filteredList = array();
        foreach($selectors as $selector)
        {
            $results = static::filterList($domList, $selector, $filteredList);
            $list[$selector->name] = $results;
        }

        return $list;
    }
}
