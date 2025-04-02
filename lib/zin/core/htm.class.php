<?php
declare(strict_types=1);
/**
 * The text element class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'node.class.php';
require_once __DIR__ . DS . 'directive.class.php';
require_once __DIR__ . DS . 'context.func.php';

class htm extends node
{
    public function addToBlock(string $name, mixed $child, bool $prepend = false)
    {
        if(is_string($child))
        {
            $child = (object)array('html' => $child);
        }
        return parent::addToBlock($name, $child, $prepend);
    }
}

function html(mixed ...$codes): htm
{
    return new htm(...$codes);
}

function rawContent(): htm
{
    context()->rawContentCalled = true;
    return h::comment('{{RAW_CONTENT}}');
}

function hookContent(): htm
{
    context()->hookContentCalled = true;
    return h::comment('{{HOOK_CONTENT}}');
}

function rParse($rawContent)
{
    $rawContents = [];
    /* 解析所有的 rStart 标记，提取内容块。 */
    $rStartPattern = '/<!-- \{RTOP:(\w+)\} -->(.*?)<!-- \{RBOTTOM:\1\} -->/s';
    if(preg_match_all($rStartPattern, $rawContent, $matches, PREG_SET_ORDER))
    {
        foreach($matches as $match)
        {
            $name = $match[1];
            $content = $match[2];
            $rawContents[$name] = $content;
        }
    }

    return $rawContents;
}

function rTop($name)
{
    return h::comment('{RTOP:' . $name . '}');
}

function rBottom($name)
{
    return h::comment('{RBOTTOM:' . $name . '}');
}

function rHolder($name)
{
    context()->rawContentCalled = true;
    $name = strtoupper($name);
    return "{RCONTENT_{$name}}";
}
