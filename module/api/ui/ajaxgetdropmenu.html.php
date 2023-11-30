<?php
declare(strict_types=1);
/**
 * The ajaxgetdropmenu view file of api module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

$data = array('normal' => array(), 'closed' => array());
$data['normal'][] = array('id' => 0, 'text' => $lang->api->noLinked, 'active' => $objectID == 0, 'type' => 'nolink');

/* 处理分组数据。Process grouped data. */
foreach(array('product', 'project') as $moduleType)
{
    foreach($normalObjects[$moduleType] as $normalObjectID => $normalObjectName)
    {
        $item = array();
        $item['id']     = $normalObjectID;
        $item['text']   = $normalObjectName;
        $item['active'] = $normalObjectID == $objectID;
        $item['type']   = $moduleType;
        $item['keys']   = zget(common::convert2Pinyin(array($normalObjectName)), $normalObjectName, '');

        $data['normal'][] = $item;
    }

    foreach($closedObjects[$moduleType] as $closedObjectID => $closedObjectName)
    {
        $item = array();
        $item['id']     = $closedObjectID;
        $item['text']   = $closedObjectName;
        $item['active'] = $closedObjectID == $objectID;
        $item['type']   = $moduleType;
        $item['keys']   = zget(common::convert2Pinyin(array($closedObjectName)), $closedObjectName, '');

        $data['closed'][] = $item;
    }
}

$tabs = array();
$tabs[] = array('name' => 'normal', 'text' => '');
$tabs[] = array('name' => 'closed', 'text' => $lang->doc->closed);

/**
 * 定义最终的 JSON 数据。
 * Define the final json data.
 */
$link = $this->createLink('api', 'ajaxGetList', "objectID={id}&objectType={type}");

$json = array();
$json['data']       = $data;
$json['tabs']       = $tabs;
$json['searchHint'] = $lang->searchAB;
$json['link']       = $link;
$json['itemType']   = 'api';
$json['expandName'] = 'closed';

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render json data to string and send to client.
 */
renderJson($json);
