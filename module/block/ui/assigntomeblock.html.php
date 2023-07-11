<?php
declare(strict_types=1);
/**
* The assigntome view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;
jsVar('moreLabel', $lang->more);

$blockNavCode = 'nav-' . uniqid();

$index = 0;
$count = count($hasViewPriv);
$menus = array();
$moreMenus = array();
foreach($hasViewPriv as $type => $bool)
{
    $selected = key($hasViewPriv);
    if(($longBlock && $count > 9 && $index >= 8) || (!$longBlock && $count > 4 && $index >= 3))
    {
        $moreMenus[] = array('text' => $type == 'review' ? $lang->my->audit : zget($lang->block->availableBlocks, $type), 'data-toggle' => 'tab', 'href' => "#assigntome{$type}Tab{$blockNavCode}");
    }
    else
    {
        $menus[]  = li
        (
            set('class', 'nav-item nav-switch'),
            a
            (
                set('class', $type == $selected ? 'active' : ''),
                set('data-toggle', 'tab'),
                set('href', "#assigntome{$type}Tab{$blockNavCode}"),
                $type == 'review' ? $lang->my->audit : zget($lang->block->availableBlocks, $type)
            )
        );
    }
    $index ++;
}

if(($longBlock && $count > 9) || (!$longBlock && $count > 4))
{
    $menus[]  = li
    (
        set('class', 'nav-item nav-switch'),
        a
        (
            set('data-toggle', 'dropdown'),
            set('href', "#assigntomeMenuMore{$blockNavCode}"),
            span($lang->more),
            icon('caret-down')
        ),
        menu
        (
            set::id("#assigntomeMenuMore{$blockNavCode}"),
            set::class('dropdown-menu'),
            set::items($moreMenus)
        )
    );
}

$contents = array();
foreach($hasViewPriv as $type => $bool)
{
    $data       = ${"{$type}s"};
    $configType = $type;
    if($type == 'story')       $data       = $stories;
    if($type == 'requirement') $configType = 'story';

    if(empty($config->block->{$configType}->dtable->fieldList)) continue;
    if(empty($data)) $data = array();

    $selected  = key($hasViewPriv);
    $contents[] = div
    (
        set('class', 'tab-pane ' . ($type == $selected ? 'active' : '')),
        set('id', "assigntome{$type}Tab{$blockNavCode}"),
        dtable
        (
            set::height(318),
            set::bordered(false),
            set::horzScrollbarPos('inside'),
            set::cols(array_values($config->block->{$configType}->dtable->fieldList)),
            set::data(array_values($data)),
            set::userMap($users),
        )
    );
}

panel
(
    set('class', 'assigntome-block'),
    set('headingClass', 'border-b'),
    set('bodyClass', 'p-0'),
    to::heading
    (
        div
        (
            set('class', 'panel-title flex justify-between w-full'),
            $block->title,
            div
            (
                ul
                (
                    set('class', 'nav nav-tabs'),
                    $menus
                )
            )
        )
    ),
    div($contents)
);

render();
