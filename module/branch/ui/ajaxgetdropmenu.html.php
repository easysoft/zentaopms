<?php
declare(strict_types=1);
/**
 * The ajaxgetdropmenu view file of branch module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

$data = array('active' => array(), 'closed' => array());

/* 处理分组数据。Process grouped data. */
foreach($branches as $branchID => $branchName)
{
    $item = array();
    $item['id']        = $branchID;
    $item['text']      = $branchName;
    $item['type']      = 'branch';
    $item['keys']      = zget($branchesPinyin, $branchName, '');
    $item['active']    = $branchID == $currentBranchID || (empty($branchID) && empty($currentBranchID));

    if($branchID == 'all' || empty($branchID) || $statusList[$branchID] == 'active')
    {
        $data['active'][] = $item;
    }
    else
    {
        $data['closed'][] = $item;
    }
}

$tabs = array();
$tabs[] = array('name' => 'active', 'text' => '');
$tabs[] = array('name' => 'closed', 'text' => $lang->branch->closed);

/**
 * 定义最终的 JSON 数据。
 * Define the final json data.
 */

$json = array();
$json['data']       = $data;
$json['tabs']       = $tabs;
$json['searchHint'] = $lang->searchAB;
$json['link']       = $link;
$json['itemType']   = 'branch';
$json['expandName'] = 'closed';

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render json data to string and send to client.
 */
renderJson($json);
