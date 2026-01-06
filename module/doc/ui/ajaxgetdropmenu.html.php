<?php
declare(strict_types=1);
/**
 * The ajaxgetdropmenu view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

/**
 * 定义每个分组下的选项数据列表。
 * Define the grouped data list.
 */
$data = array('normal' => array(), 'closed' => array());

$normalObjectNames = array_values($normalObjects);
$normalObjectPinyinNames = common::convert2Pinyin($normalObjectNames);

/* 处理分组数据。Process grouped data. */
foreach($normalObjects as $normalObjectID => $normalObjectName)
{
    $item = array();
    $item['id']     = $normalObjectID;
    $item['text']   = $normalObjectName;
    $item['active'] = $normalObjectID == $objectID;
    $item['keys']   = zget($normalObjectPinyinNames, $normalObjectName, '');

    $data['normal'][] = $item;
}

$closedObjectNames = array_values($closedObjects);
$closedObjectPinyinNames = common::convert2Pinyin($closedObjectNames);

foreach($closedObjects as $closedObjectID => $closedObjectName)
{
    $item = array();
    $item['id']     = $closedObjectID;
    $item['text']   = $closedObjectName;
    $item['active'] = $closedObjectID == $objectID;
    $item['keys']   = zget($closedObjectPinyinNames, $closedObjectName, '');

    $data['closed'][] = $item;
}

/* 将分组数据转换为索引数组。Format grouped data to indexed array. */

/**
 * 定义每个分组名称信息，包括可展开的已关闭分组。
 * Define every group name, include expanded group.
 */
$tabs = array();
$tabs[] = array('name' => 'normal', 'text' => '');
$tabs[] = array('name' => 'closed', 'text' => $lang->doc->closed);

/**
 * 定义最终的 JSON 数据。
 * Define the final json data.
 */
$params = "objectID=%s";
if($method == 'showfiles' || $objectType == 'custom') $params = "type=$objectType&objectID=%s";
if($method == 'create') $params = "objectType=$objectType&objectID=%s&libID=$libID&moduleID=0";
$link = $this->createLink($module, $method, $params);

$json = array();
$json['data']       = $data;
$json['tabs']       = $tabs;
$json['searchHint'] = $lang->searchAB;
$json['link']       = sprintf($link, '{id}');
$json['expandName'] = 'closed';
$json['itemType']   = 'doc';

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render json data to string and send to client.
 */
renderJson($json);
