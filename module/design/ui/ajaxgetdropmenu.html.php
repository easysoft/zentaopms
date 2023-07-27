<?php
declare(strict_types=1);
/**
 * The ajaxGetDropMenu view file of design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     design
 * @link        https://www.zentao.net
 */
namespace zin;

/**
 * 定义每个分组下的选项数据列表。
 * Define the grouped data list.
 */
$data = array('my' => array(), 'other' => array(), 'closed' => array());

foreach($products as $product)
{
    $item = array();
    $item['id']     = $product->id;
    $item['text']   = $product->name;
    $item['active'] = $productID == $product->id;
    $item['type']   = 'product';
    $item['keys']   = zget(common::convert2Pinyin(array($product->name)), $product->name, '');
    if($product->status == 'normal' and $product->PO == $this->app->user->account)
    {
        $data['my'][] = $item;
    }
    else if($product->status == 'normal' and !($product->PO == $this->app->user->account))
    {
        $data['other'][] = $item;
    }
    else if($product->status == 'closed')
    {
        $data['closed'][] = $item;
    }
}

/**
 * 定义每个分组名称信息，包括可展开的已关闭分组。
 * Define every group name, include expanded group.
 */
$tabs = array();
$tabs[] = array('name' => 'closed', 'text' => $lang->product->closedProduct);
if(!empty($data['other']) && !empty($data['my']))
{
    $tabs[] = array('name' => 'my',    'text' => $lang->product->mine);
    $tabs[] = array('name' => 'other', 'text' => $lang->product->other);
}
elseif(!empty($data['other']))
{
    $tabs[] = array('name' => 'other', 'text' => '');
}
else
{
    $tabs[] = array('name' => 'my',    'text' => '');
}

$json = array();
$json['data']       = $data;
$json['tabs']       = $tabs;
$json['searchHint'] = $lang->searchAB;
$json['expandName'] = 'closed';
$json['itemType']   = 'product';
$json['link']       = array('product' => sprintf($link, '{id}'));

renderJson($json);
