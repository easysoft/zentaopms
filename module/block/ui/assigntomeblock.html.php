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
jsVar('todayLabel', $lang->today);
jsVar('yesterdayLabel', $lang->yesterday);

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
            setClass('nav-item nav-switch'),
            a
            (
                setClass($type == $selected ? 'active' : ''),
                setData(array('toggle' => 'tab')),
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
        setClass('nav-item nav-switch'),
        a
        (
            setData(array('toggle' => 'dropdown')),
            set('href', "#assigntomeMenuMore{$blockNavCode}"),
            span($lang->more),
            icon('caret-down')
        ),
        menu
        (
            setID("assigntomeMenuMore{$blockNavCode}"),
            setClass('dropdown-menu'),
            set::items($moreMenus)
        )
    );
}

$contents = array();
foreach($hasViewPriv as $type => $bool)
{
    $data       = ${"{$type}s"};
    $configType = $type;
    if($type == 'story')       $data = $stories;
    if($type == 'testcase')    $configType = 'case';
    if($type == 'requirement') $configType = 'story';

    if(empty($config->block->{$configType}->dtable->fieldList)) continue;
    if(!$longBlock && !empty($config->block->{$configType}->dtable->short->fieldList)) $config->block->{$configType}->dtable->fieldList = $config->block->{$configType}->dtable->short->fieldList;
    if(empty($data)) $data = array();

    if($type == 'review')
    {
        $statusList = array();
        foreach($data as $review)
        {
            $reviewType = $review->type;
            if($reviewType == 'projectreview') $reviewType = 'review';

            $typeName = '';
            if(isset($lang->{$review->type}->common)) $typeName = $lang->{$review->type}->common;
            if($reviewType == 'story')                $typeName = $review->storyType == 'story' ? $lang->SRCommon : $lang->URCommon;

            if(isset($lang->$reviewType->statusList)) $statusList = array_merge($statusList, $lang->$reviewType->statusList);
            if($reviewType == 'attend')               $statusList = array_merge($statusList, $lang->attend->reviewStatusList);
            if(!in_array($reviewType, array('story', 'testcase', 'feedback', 'review')) and strpos(",{$config->my->oaObjectType},", ",$reviewType,") === false) $statusList = array_merge($statusList, $lang->approval->nodeList);

            $review->type = $typeName;
        }
        $config->block->review->dtable->fieldList['status']['statusMap'] = $statusList;
    }

    $selected  = key($hasViewPriv);
    $contents[] = div
    (
        setClass('tab-pane ' . ($type == $selected ? 'active' : '')),
        setID("assigntome{$type}Tab{$blockNavCode}"),
        dtable
        (
            set::height(318),
            set::bordered(false),
            $type == 'todo'  || $type == 'task' ? set::fixedLeftWidth('0.44') : '',
            set::horzScrollbarPos('inside'),
            set::onRenderCell(jsRaw('window.renderCell')),
            set::cols(array_values($config->block->{$configType}->dtable->fieldList)),
            set::data(array_values($data)),
            set::userMap($users)
        )
    );
}

blockPanel
(
    setClass('assigntome-block list-block'),
    to::heading
    (
        div
        (
            ul
            (
                setClass('nav nav-tabs'),
                $menus
            )
        )
    ),
    div($contents)
);

render();
