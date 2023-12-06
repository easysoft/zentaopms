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

$data = array();
foreach($spaceList as $spaceID => $space)
{
    $spaceItem = array();
    $spaceItem['type']  = $lang->kanban->spaceCommon;
    $spaceItem['text']  = $space;
    $spaceItem['items'] = array();

    $data['other'][$spaceID] = $spaceItem;

    $kanbans = zget($kanbanList, $spaceID, array());
    foreach($kanbans as $id => $kanban)
    {
        $item = array();
        $item['id']    = $kanban->id;
        $item['text']  = $kanban->name;
        $item['keys']  = zget(common::convert2Pinyin(array($kanban->name)), $kanban->name, '');

        $data['other'][$spaceID]['items'][] = $item;
    }
}

/* 将分组数据转换为索引数组。Format grouped data to indexed array. */
foreach ($data as $key => $value) $data[$key] = array_values($value);

/**
 * 定义最终的 JSON 数据。
 * Define the final json data.
 */
$json = array();
$json['data']       = $data;
$json['tabs']       = array(array('name' => 'other'));
$json['searchHint'] = $lang->searchAB;
$json['link']       = array('kanban' => sprintf($link, '{id}'));
$json['itemType']   = 'kanban';

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render json data to string and send to client.
 */
renderJson($json);
