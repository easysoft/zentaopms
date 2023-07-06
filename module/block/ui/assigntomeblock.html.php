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
$blockNavCode = 'nav-' . uniqid();

$menus = array();
foreach($hasViewPriv as $type => $bool)
{
    $selected = key($hasViewPriv);
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

$contents = array();
foreach($hasViewPriv as $type => $bool)
{
    if(empty($config->block->{$type}->dtable->fieldList) || empty(${"{$type}s"})) continue;

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
            set::cols(array_values($config->block->{$type}->dtable->fieldList)),
            set::data(array_values(${"{$type}s"})),
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
            set('class', 'panel-title flex w-full'),
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
