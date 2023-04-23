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

require_once dirname(__DIR__) . DS . 'utils' . DS . 'deep.func.php';
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

    public $renderType;

    public $dataGetters = NULL;

    public $dataCommands;

    /**
     * Construct the dom object.
     * @param  wg                  $wg
     * @param  array               $children
     * @param  array|string|object $selectors
     * @access public
     */
    public function __construct($wg, $children, $selectors = NULL, $renderType = NULL, $dataCommands = NULL)
    {
        $this->wg           = $wg;
        $this->renderType   = $renderType;

        $this->add($children);
        $this->addSelectors($selectors);
        $this->addDataCommands($dataCommands);
    }

    public function __debugInfo()
    {
        return
        [
            'gid'          => $this->wg->gid,
            'type'         => $this->wg->type(),
            'count'        => count($this->children),
            'renderInner'  => $this->renderInner,
            'renderType'   => $this->renderType,
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

    public function addDataCommands($commands)
    {
        if(empty($commands)) return;

        if(is_string($commands))
        {
            $commandList = explode(',', $commands);
            $commands    = [];
            foreach($commandList as $command)
            {
                $parts = explode(':', $command, 2);
                $commands[$parts[0]] = count($parts) > 1 ? $parts[1] : $parts[0];
            }
        }

        if($this->dataCommands === NULL) $this->dataCommands = [];
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

        if($this->selectors === NULL) $this->selectors = [];
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
        if($this->renderType === 'json') return $this->renderJson();
        if($this->renderType === 'list') return $this->renderList();
        return $this->renderHtml();
    }

    public function renderJson()
    {
        $list = $this->build();
        if(empty($list)) return '{}';

        $output = [];
        foreach($list as $name => $item)
        {
            $output[$name] = static::renderItemToJson($item);
        }

        if(!empty($this->dataCommands))
        {
            $data = [];
            foreach($this->dataCommands as $name => $command)
            {
                $data[$name] = data($command);
            }
            $output['data'] = $data;
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
            $result = static::renderItemToHtml($item);
            if(!is_string($result)) $result = json_encode($result);
            $output[] = $result;
        }
        return implode('', $output);
    }

    public function renderList()
    {
        $list = $this->build();
        if(empty($list)) return '[]';

        $output = [];
        foreach($list as $name => $item)
        {
            if(is_array($item) && count($item) === 1) $item = $item[0];
            $output[] = ['name' => $name, 'data' => static::renderDomItem($item)];
        }

        if(!empty($this->dataCommands))
        {
            foreach($this->dataCommands as $name => $command)
            {
                $output[] = ['name' => $name, 'data' => data($command)];
            }
        }

        return json_encode($output);
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
        if($item === NULL || is_bool($item)) return NULL;

        if(is_array($item))
        {
            $output = [];
            foreach($item as $subItem) $output[] = static::renderItemToJson($subItem);
            return $output;
        }

        if($item instanceof dom)
        {
            $json = $item->wg->toJsonData();
            if(!empty($item->dataGetters))
            {
                $output = [];
                $props = explode(',', $item->dataGetters);
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
                $item->renderType   = $selector->type ?? NULL;
                $item->dataGetters  = $selector->data ?? NULL;
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
