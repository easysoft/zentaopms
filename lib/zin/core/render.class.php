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

    public array $dataCommands = array();

    public array $filteredMap = array();

    /**
     * Construct the dom object.
     *
     * @param  node                $node
     * @param  array               $children
     * @param  array|string|object $selectors
     * @access public
     */
    public function __construct(node $node, null|object|string|array $selectors = null, string $renderType = 'html', null|string|array $dataCommands = null, bool $renderInner = false)
    {
        $this->node         = $node;
        $this->renderType   = $renderType;
        $this->renderInner  = $renderInner;

        $this->addSelectors($selectors);
        $this->addDataCommands($dataCommands);
    }

    public function handleBuildNode(stdClass $data, node $node)
    {
        if(!$this->selectors || (isset($data->removed) && $data->removed)) return;

        foreach($this->selectors as $name => $selector)
        {
            if(!isset($this->filteredMap[$name])) $this->filteredMap[$name] = array();
            $filteredNodes = $this->filteredMap[$name];

            if(($selector->id || $selector->first) && $filteredNodes) continue;
            if(!$node->isMatch($selector)) continue;

            $filteredNodes[$node->gid] = $node;
            $this->filteredMap[$name] = $filteredNodes;
        }
    }

    public function render(): string|array|object
    {
        $this->node->prebuild();

        if($this->renderType === 'list') return $this->renderList();
        if($this->renderType === 'json') return $this->renderJSON();

        return $this->renderHtml();
    }

    public function renderList(): array
    {
        $list = array();
        foreach($this->filteredMap as $name => $nodes)
        {
            $selector       = $this->selectors[$name];
            $item           = new stdClass();
            $item->name     = $name;
            $item->selector = stringifySelectors($selector);
            $item->selector = $selector;
            $item->count    = count($nodes);

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

        $dataList = $this->getDataByCommands();
        foreach($dataList as $name => $data)
        {
            $list[] = array('name' => $name, 'type' => 'data', 'data' => $data);
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
        $output = new stdClass();
        if($this->filteredMap)
        {
            foreach($this->filteredMap as $name => $nodes)
            {
                $list = array();
                foreach($nodes as $node)
                {
                    $list[] = $node->toJSON();
                }
                $output->$name = $list;
            }
        }

        $output->data = $this->getDataByCommands();
        return $output;
    }

    public function renderHtml(): string
    {
        if($this->filteredMap) return renderToHtml(array_values($this->filteredMap));
        return $this->node->render();
    }

    public function addDataCommands(null|string|array $commands)
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

        $index = 0;
        foreach($commands as $key => $command)
        {
            $this->dataCommands[$index == $key ? $command : $key] = $command;
            $index++;
        }
    }

    public function addSelectors(null|object|string|array $selectors)
    {
        if(!$selectors) return;

        $selectors = parseSelectors($selectors);
        foreach($selectors as $selector)
        {
            if(isset($selector->command) && !empty($selector->command)) $this->addDataCommands(array($selector->tag => $selector->command));
            else $this->selectors[$selector->name] = $selector;
        }
    }

    protected function getDataByCommands()
    {
        $data = array();
        foreach($this->dataCommands as $name => $command)
        {
            $data[$name] = data($command);
        }

        return $data;
    }
}
