<?php
declare(strict_types=1);
/**
 * The ajaxSwithcherMenu view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin<zhouxin@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

/**
 * 定义每个分组下的选项数据列表。
 * Define the grouped data list.
 */

$data = array('normal' => array(), 'closed' => array());

if(count($products) > 1)
{
    $defaultItem = array();
    $defaultItem['id']     = 0;
    $defaultItem['text']   = $lang->product->all;
    $defaultItem['active'] = $productID == 0;
    $defaultItem['type']   = 'product';

    $data['normal'][] = $defaultItem;
}

foreach($products as $product)
{
    $item = array();
    $item['id']     = $product->id;
    $item['text']   = $product->name;
    $item['active'] = $productID == $product->id;
    $item['type']   = 'product';
    $item['keys']   = zget(common::convert2Pinyin(array($product->name)), $product->name, '');

    if($product->status == 'closed')
    {
        $data['closed'][] = $item;
    }
    else
    {
        $data['normal'][] = $item;
    }
}

/**
 * 定义每个分组名称信息，包括可展开的已关闭分组。
 * Define every group name, include expanded group.
 */
$tabs   = array();
$tabs[] = array('name' => 'closed', 'text' => $lang->product->closedProduct);
$tabs[] = array('name' => 'normal', 'text' => '');

$json = array();
$json['data']       = $data;
$json['tabs']       = $tabs;
$json['searchHint'] = $lang->searchAB;
$json['expandName'] = 'closed';
$json['itemType']   = 'product';
$json['link']       = array('product' => sprintf($link, '{id}'));

renderJson($json);
