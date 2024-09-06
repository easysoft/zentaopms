<?php
/**
 * The ajaxgetdropmenu view file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     kanban
 * @version     $Id
 * @link        https://www.zentao.net
 */
namespace zin;

$getKanbanGroup = function(object $kanban): string
{
    if($kanban->status == 'closed') return 'closed';
    if($kanban->owner == $this->app->user->account) return 'my';
    return 'other';
};

$data        = array();
$kanbanGroup = array();
foreach($spaceList as $spaceID => $space)
{
    $spaceItem = array();
    $spaceItem['type']  = $lang->kanban->spaceCommon;
    $spaceItem['text']  = $space;
    $spaceItem['items'] = array();

    $kanbans = zget($kanbanList, $spaceID, array());
    foreach($kanbans as $id => $kanban)
    {
        $kanbanType = $kanbanGroup[$id] = $getKanbanGroup($kanban);

        $item = array();
        $item['id']    = $kanban->id;
        $item['text']  = $kanban->name;
        $item['keys']  = zget(common::convert2Pinyin(array($kanban->name)), $kanban->name, '');

        if(!isset($data[$kanbanType][$spaceID])) $data[$kanbanType][$spaceID] = $spaceItem;
        $data[$kanbanType][$spaceID]['items'][] = $item;
    }
}


/**
 * 定义每个分组名称信息，包括可展开的已关闭分组。
 * Define every group name, include expanded group.
 */
$tabs = array();
$tabs[] = array('name' => 'my',     'text' => $lang->kanban->my, 'active' => zget($kanbanGroup, $kanbanID, '') === 'my');
$tabs[] = array('name' => 'other',  'text' => $lang->kanban->other, 'active' => zget($kanbanGroup, $kanbanID, '') === 'other');
$tabs[] = array('name' => 'closed', 'text' => $lang->kanban->closed);

/* 将分组数据转换为索引数组。Format grouped data to indexed array. */
foreach ($data as $key => $value) $data[$key] = array_values($value);

/**
 * 定义最终的 JSON 数据。
 * Define the final json data.
 */
$json = array();
$json['data']       = $data;
$json['tabs']       = $tabs;
$json['searchHint'] = $lang->searchAB;
$json['link']       = array('kanban' => sprintf($link, '{id}'));
$json['expandName'] = 'closed';
$json['itemType']   = 'kanban';

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render json data to string and send to client.
 */
renderJson($json);
