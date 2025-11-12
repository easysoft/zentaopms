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

require_once dirname(__DIR__) . DS . 'utils' . DS . 'deep.func.php';
require_once __DIR__ . DS . 'selector.func.php';
require_once __DIR__ . DS . 'node.class.php';
require_once __DIR__ . DS . 'context.func.php';

class render
{
    public node $node;

    public bool $renderInner;

    public string $renderType;

    public array $selectors = array();

    public array $filteredMap = array();

    /**
     * Construct the dom object.
     *
     * @param  node                $node
     * @param  array               $children
     * @param  array|string|object $selectors
     * @access public
     */
    public function __construct(node $node, null|object|string|array $selectors = null, string $renderType = 'html', bool $renderInner = false)
    {
        $this->node         = $node;
        $this->renderType   = $renderType;
        $this->renderInner  = $renderInner;

        $this->addSelectors($selectors);
    }

    public function handleBuildNode(stdClass $data, node $node)
    {
        if(!$this->selectors || (isset($data->removed) && $data->removed)) return;

        foreach($this->selectors as $name => $selector)
        {
            if(isset($selector->command)) continue;
            if(!isset($this->filteredMap[$name])) $this->filteredMap[$name] = array();
            $filteredNodes = $this->filteredMap[$name];

            if(!$node->isMatch($selector)) continue;
            if($node instanceof h && $node->parent)
            {
                if($node->parent instanceof wg && $node->parent->isMatch($selector)) continue;
                if($node->parent->parent && $node->parent->parent instanceof wg && $node->parent->parent->isMatch($selector)) continue;
            }

            if($selector->id || $selector->first) $filteredNodes = array();
            $filteredNodes[$node->gid] = $node;
            $this->filteredMap[$name] = $filteredNodes;
        }
    }

    public function render(): string|array|object
    {
        $this->node->prebuild();

        if($this->renderType === 'list') return $this->renderList();
        if($this->renderType === 'json') return $this->renderJson();

        return $this->renderHtml();
    }

    public function renderList(): array
    {
        global $app;

        $isUserLogon = isset($app->user) && $app->user->account != 'guest';
        $list        = array();

        foreach($this->selectors as $selector)
        {
            $name = $selector->name;
            if(isset($selector->command))
            {
                $item = new stdClass();
                $item->name     = $name;
                $item->type     = 'data';
                $item->selector = $selector->selector;
                $item->data     = (!$isUserLogon || $selector->disabled) ? null : data($selector->command);
                $list[] = $item;
            }
            else
            {
                $nodes          = $this->filteredMap[$name];
                $item           = new stdClass();
                $item->name     = $name;
                $item->selector = stringifySelectors($selector);

                if(isset($selector->options['type']) && $selector->options['type'] === 'json')
                {
                    $item->type  = 'json';
                    $item->data  = array();
                    $dataGetters = isset($selector->options['data']) ? $selector->options['data'] : null;
                    $dataPorps   = $dataGetters ? explode('|', $dataGetters) : null;

                    foreach($nodes as $node)
                    {
                        $json = $node->toJSON();
                        if($dataPorps)
                        {
                            $data = array();
                            foreach($dataPorps as $prop)
                            {
                                $prop = trim($prop);
                                if(empty($prop)) continue;

                                $parts       = explode('~', $prop, 2);
                                $name        = $parts[0];
                                $namePath    = count($parts) > 1 ? $parts[1] : $parts[0];
                                $data[$name] = \zin\utils\deepGet($json, $namePath);
                            }
                            $item->data[] = $data;
                        }
                        else
                        {
                            $item->data[] = $json;
                        }
                    }

                    if(count($item->data) === 1) $item->data = $item->data[0];
                }
                else
                {
                    $html = array();
                    foreach($nodes as $node)
                    {
                        $html[] = $selector->inner ? $node->renderInner() : $node->render();
                    }
                    $item->type = 'html';
                    $item->data = implode("\n", $html);
                }

                $list[] = $item;
            }
        }

        return $list;
    }

    /**
     * Render dom to json object.
     *
     * @access public
     * @return object
     */
    public function renderJson(): object
    {
        global $app;

        $isUserLogon = isset($app->user) && $app->user->account != 'guest';
        $output      = new stdClass();

        $output->data = array();
        foreach($this->selectors as $selector)
        {
            $name = $selector->name;
            if(isset($selector->command))
            {
                $output->data[$name] = (!$isUserLogon || $selector->disabled) ? null : data($selector->command);
            }
            else
            {
                $nodes = $this->filteredMap[$name];
                $list  = array();
                foreach($nodes as $node)
                {
                    $list[] = $node->toJSON();
                }
                $output->$name = $list;
            }
        }

        return $output;
    }

    public function renderHtml(): string
    {
        if($this->filteredMap) return renderToHtml(array_values($this->filteredMap));
        return $this->node->render();
    }

    public function addSelectors(null|object|string|array $selectors)
    {
        if(!$selectors) return;

        global $config;

        $selectors = parseSelectors($selectors);
        foreach($selectors as $selector)
        {
            $selector->disabled = !empty($selector->command) && !in_array($selector->command, $config->zin->allowCommands);
            $this->selectors[$selector->name] = $selector;
        }
    }
}
