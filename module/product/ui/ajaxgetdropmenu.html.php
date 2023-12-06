<?php
declare(strict_types=1);
/**
 * The ajaxgetdropmenu view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     product
 * @version     $Id
 * @link        https://www.zentao.net
 */
namespace zin;

/**
 * 获取产品所属分组。
 * Get product group.
 *
 * @param object $product
 * @return string
 */
$getProductGroup = function($product): string
{
    global $app;
    if($product->status == 'normal' and $product->PO == $app->user->account) return 'my';
    if($product->status == 'closed') return 'closed';
    return 'other';
};

/**
 * 定义每个分组下的选项数据列表。
 * Define the grouped data list.
 */
$data = array('my' => array(), 'other' => array(), 'closed' => array());

/* 处理分组数据。Process grouped data. */
foreach($products as $programID => $programProducts)
{
    $programItem = array();
    $programItem['id']    = $programID;
    $programItem['type']  = 'program';
    $programItem['text']  = $programID ? zget($programs, $programID) : $lang->product->emptyProgram;
    $programItem['items'] = array();

    if(!$programID) $programItem['label'] = '';

    foreach($programProducts as $index => $product)
    {
        $group = $getProductGroup($product);
        $name  = (in_array($this->config->systemMode, array('ALM', 'PLM')) and $product->line) ? zget($lines, $product->line, '') . ' / ' . $product->name : $product->name;

        $item = array();
        $item['id']     = $product->id;
        $item['text']   = $product->name;
        $item['active'] = $productID == $product->id;
        $item['keys']   = zget(common::convert2Pinyin(array($product->name)), $product->name, '');

        if(!isset($data[$group][$programID])) $data[$group][$programID] = $programItem;
        $data[$group][$programID]['items'][] = $item;
    }
}

/* 将分组数据转换为索引数组。Format grouped data to indexed array. */
foreach ($data as $key => $value) $data[$key] = array_values($value);

/**
 * 定义每个分组名称信息，包括可展开的已关闭分组。
 * Define every group name, include expanded group.
 */
$tabs = array();
$tabs[] = array('name' => 'my',     'text' => $lang->product->mine);
$tabs[] = array('name' => 'other',  'text' => $lang->product->other);
$tabs[] = array('name' => 'closed', 'text' => $lang->product->closedProduct);

/**
 * 定义最终的 JSON 数据。
 * Define the final json data.
 */
$json = array();
$json['data']       = $data;
$json['tabs']       = $tabs;
$json['searchHint'] = $lang->searchAB;
$json['link']       = array('product' => sprintf($link, '{id}'));
$json['labelMap']   = array('program' => $lang->program->common);
$json['expandName'] = 'closed';
$json['itemType']   = 'product';

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render json data to string and send to client.
 */
renderJson($json);
