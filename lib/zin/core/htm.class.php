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

/**
 * 生成 rawContent 标记，可以指定名称，如果不指定则指向全局内容。
 * Generate rawContent tag, you can specify the name, if not specified, it will point to the global content.
 *
 * @param string|null $name 标记名称
 * @return htm 包含 rawContent 标记的 htm 对象
 */
function rawContent(?string $name = null): htm
{
    if($name)
    {
        if(!isset(context()->rawContentNames[$name])) context()->rawContentNames[$name] = 0;
        return h::comment('{{RAW_CONTENT:' . $name . '}}');
    }
    context()->rawContentCalled = true;
    return h::comment('{{RAW_CONTENT}}');
}

/**
 * 生成 rawContent 开始标记。
 * Generate rawContent start tag.
 *
 * @param string $name 标记名称
 * @return void
 */
function rawContentStart(string $name)
{
    context()->rawContentNames[$name] = 0; // 0 means start.
    echo "<!-- {{RAW_CONTENT_START:$name}} -->";
}

/**
 * 生成 rawContent 结束标记。
 * Generate rawContent end tag.
 *
 * @param string $name 标记名称
 * @return void
 */
function rawContentEnd(string $name)
{
    $rawContentNames = context()->rawContentNames;
    if(isDebug())
    {
        $last     = end($rawContentNames);
        $lastName = key($rawContentNames);
        if($lastName !== $name) triggerError("rawContentEnd(\"$name\") end called without rawContentStart(\"$lastName\").");
        elseif($last === 1) triggerError("rawContentEnd(\"$name\") already called.");
    }
    $rawContentNames[$name] = 1; // 1 means end.
    echo "<!-- {{RAW_CONTENT_END:$name}} -->";
}

/**
 * 解析 rawContent 标记，提取内容块。
 * Parse rawContent tag, extract content block.
 *
 * @param string $rawContent 原始内容字符串
 * @return array 包含所有内容块的数组，键为块名，值为块内容
 */
function parseRawContent(string $rawContent): array
{
    $map = array('GLOBAL' => '');
    $offset = 0;
    $lastNoEndName = '';
    while($offset <= strlen($rawContent))
    {
        $startResult = preg_match('/<!-- \{\{RAW_CONTENT_START:([a-zA-Z0-9_]+)\}\} -->\n?/', $rawContent, $matches, PREG_OFFSET_CAPTURE, $offset);
        if($startResult !== 1)
        {
            if(!$lastNoEndName) $map['GLOBAL'] .= substr($rawContent, $offset);
            break;
        }

        if($lastNoEndName) $map[$lastNoEndName] = substr($rawContent, $offset, $matches[0][1] - $offset - (($offset > 0 && $rawContent[$offset - 1] === "\n") ? 1 : 0));
        elseif($offset === 0 && $matches[0][1] > 0) $map['GLOBAL'] = substr($rawContent, 0, $matches[0][1]);

        $name      = $matches[1][0];
        $offset    = $matches[0][1] + strlen($matches[0][0]);
        $endResult = preg_match("/\n?<!-- \{\{RAW_CONTENT_END:$name\}\} -->\n?/", $rawContent, $matches, PREG_OFFSET_CAPTURE, $offset);
        if($endResult === 1)
        {
            $map[$name]    = substr($rawContent, $offset, $matches[0][1] - $offset);
            $offset        = $matches[0][1] + strlen($matches[0][0]);
            $lastNoEndName = '';
        }
        else
        {
            $lastNoEndName = $name;
        }
    }

    if($lastNoEndName) $map[$lastNoEndName] = substr($rawContent, $offset);

    return $map;
}

function hookContent(): htm
{
    context()->hookContentCalled = true;
    return h::comment('{{HOOK_CONTENT}}');
}
