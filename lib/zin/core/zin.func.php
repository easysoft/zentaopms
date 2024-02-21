<?php
declare(strict_types=1);
/**
 * The widget function file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'flat.func.php';
require_once __DIR__ . DS . 'loader.func.php';
require_once __DIR__ . DS . 'node.class.php';
require_once __DIR__ . DS . 'text.class.php';
require_once __DIR__ . DS . 'props.class.php';
require_once __DIR__ . DS . 'directive.class.php';
require_once __DIR__ . DS . 'setting.class.php';
require_once __DIR__ . DS . 'set.class.php';
require_once __DIR__ . DS . 'wg.class.php';
require_once __DIR__ . DS . 'h.class.php';
require_once __DIR__ . DS . 'h.func.php';
require_once __DIR__ . DS . 'item.class.php';
require_once __DIR__ . DS . 'to.class.php';
require_once __DIR__ . DS . 'context.func.php';
require_once __DIR__ . DS . 'on.class.php';
require_once __DIR__ . DS . 'jquery.class.php';

/**
 * Create block content.
 *
 * @param  string       $name
 * @param  mixed        ...$args
 * @return directive
 */
function to(string $blockName, mixed ...$args): directive
{
    $node = new node(...$args);
    return directive('block', array($blockName => $node));
}

/**
 * Create content for block "before".
 *
 * @param  string       $args
 * @return directive
 */
function before(mixed ...$args): directive
{
    return to('before', ...$args);
}

/**
 * Create content for block "after".
 *
 * @param  string       $args
 * @return directive
 */
function after(mixed ...$args): directive
{
    return to('after', ...$args);
}

/**
 * Create widget contents inherited from the given widget.
 *
 * @param  node|array $item
 * @return array
 */
function inherit(node|array $item): array
{
    if(!($item instanceof node)) $item = new node($item);
    return array(set($item->props), directive('block', $item->blocks), $item->children());
}

/**
 * Divorce widget from parent.
 *
 * @param  node|array $item
 * @return array
 */
function divorce(node|array $item): node|array
{
    if($item instanceof node)
    {
        $item->parent = null;
    }
    else if(is_array($item))
    {
        foreach($item as $i) divorce($i);
    }
    return $item;
}

/**
 * Group widgets by type.
 *
 * @param  wg|array $items
 * @param  string   $types
 * @return array
 */
function groupWgInList(wg|array $items, string|array $types): array
{
    if(is_string($types)) $types = explode(',', $types);
    $typesMap = array();
    $restList = array();

    foreach($types as $type) $typesMap[$type] = array();

    foreach($items as $item)
    {
        if(!($item instanceof wg)) continue;

        $type = $item->shortType();
        if(isset($typesMap[$type])) $typesMap[$type][] = $item;
        else $restList[] = $item;
    }

    $groups = array();
    foreach($types as $index => $type) $groups[] = $typesMap[$type];
    $groups[] = $restList;
    return $groups;
}

/**
 * Include hooks files.
 */
function includeHooks()
{
    $hookFiles = context::current()->getHookFiles();
    ob_start();
    foreach($hookFiles as $hookFile)
    {
        if(!empty($hookFile) && file_exists($hookFile)) include $hookFile;
    }
    $hookCode = ob_get_clean();
    return html($hookCode);
}
