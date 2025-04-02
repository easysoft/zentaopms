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

/**
 * 解析 rawContent 中的 rStart 和 rBottom 标记，提取内容块。
 *
 * @param string $rawContent 原始内容字符串
 * @return array 包含所有内容块的数组，键为块名，值为块内容
 */
function rParse($rawContent): array
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

/**
 * 生成 rStart 标记。
 *
 * @param string $name 标记名称
 * @return htm 包含 rStart 标记的 htm 对象
 */
function rTop($name): htm
{
    return h::comment('{RTOP:' . $name . '}');
}

/**
 * 生成 rBottom 标记。
 *
 * @param string $name 标记名称
 * @return htm 包含 rBottom 标记的 htm 对象
 */
function rBottom($name): htm
{
    return h::comment('{RBOTTOM:' . $name . '}');
}

/**
 * 生成 rContent 标记。
 *
 * @param string $name 标记名称
 * @return string 包含 rContent 标记的字符串
 */
function rHolder($name): string
{
    context()->rawContentCalled = true;
    $name = strtoupper($name);
    return "{RCONTENT_{$name}}";
}
