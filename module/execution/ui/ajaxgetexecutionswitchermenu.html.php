<?php
declare(strict_types=1);
/**
 * The ajaxGetExecutionSwitcherMenu view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

/**
 * 定义每个分组下的选项数据列表。
 * Define the grouped data list.
 */

$data = array('normal' => array(), 'closed' => array());

foreach($executions as $execution)
{
    $item = array();
    $item['id']     = $execution->id;
    $item['text']   = $execution->name;
    $item['active'] = $executionID == $execution->id;
    $item['type']   = 'execution';
    $item['icon']   = 'kanban';
    $item['keys']   = zget(common::convert2Pinyin(array($execution->name)), $execution->name, '');

    if($execution->status == 'closed')
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
$tabs[] = array('name' => 'closed', 'text' => $lang->execution->closedExecution);
$tabs[] = array('name' => 'normal', 'text' => '');

$json = array();
$json['data']       = $data;
$json['tabs']       = $tabs;
$json['searchHint'] = $lang->searchAB;
$json['expandName'] = 'closed';
$json['itemType']   = 'execution';
$json['link']       = array('execution' => sprintf($link, '{id}'));

renderJson($json);
