<?php
declare(strict_types=1);
/**
 * The ajaxgetspacemenu view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun <sunguangming@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

/**
 * 定义每个分组下的选项数据列表。
 * Define the grouped data list.
 */
$data = array('normal' => array());

/* 处理分组数据。Process grouped data. */
foreach($spaces as $id => $name)
{
    $item = array();
    $item['id']       = $id;
    $item['text']     = $name;
    $item['selected'] = $id == $libID;
    $item['keys']     = zget(common::convert2Pinyin(array($name)), $name, '');

    $data['normal'][] = $item;
}

/**
 * 定义每个分组名称信息，包括可展开的已关闭分组。
 * Define every group name, include expanded group.
 */
$tabs   = array();
$tabs[] = array('name' => 'normal', 'text' => '');

/**
 * 定义最终的 JSON 数据。
 * Define the final json data.
 */
if($extra == 'doctemplate')
{
    $link = $this->createLink('doctemplate', 'browse', "objectID=%s");
}
else
{
    $link = $this->createLink('doc', 'teamSpace', "objectID=%s");
}

$json = array();
$json['data']       = $data;
$json['tabs']       = $tabs;
$json['searchHint'] = $lang->searchAB;
$json['link']       = sprintf($link, '{id}');
$json['itemType']   = 'doc';

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render json data to string and send to client.
 */
renderJson($json);
