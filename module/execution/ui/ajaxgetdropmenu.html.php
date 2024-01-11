<?php
declare(strict_types=1);
/**
 * The ajaxgetdropmenu view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     execution
 * @version     $Id
 * @link        https://www.zentao.net
 */
namespace zin;

/**
 * 获取项目所属分组。
 * Get execution group.
 *
 * @param object $execution
 * @return string
 */
$getExecutionGroup = function($execution): string
{
    global $app;
    if($execution->status != 'done' and $execution->status != 'closed' and ($execution->PM == $app->user->account or isset($execution->teams[$app->user->account]))) return 'my';
    if($execution->status != 'done' and $execution->status != 'closed' and $execution->PM != $app->user->account and !isset($execution->teams[$app->user->account])) return 'other';
    if($execution->status == 'done' or $execution->status == 'closed') return 'closed';
};

/**
 * 定义每个分组下的选项数据列表。
 * Define the grouped data list.
 */
$data = array('my' => array(), 'other' => array(), 'closed' => array());

/* 处理分组数据。Process grouped data. */
foreach($projectExecutions as $projectID => $executions)
{
    $projectItem = array();
    $projectItem['id']    = $projectID;
    $projectItem['type']  = 'project';
    $projectItem['text']  = zget($projects, $projectID);
    $projectItem['items'] = array();

    foreach($executions as $index => $execution)
    {
        $group = $getExecutionGroup($execution);

        $item = array();
        $item['id']       = $execution->id;
        $item['text']     = $execution->name;
        $item['keys']     = zget(common::convert2Pinyin(array($execution->name)), $execution->name, '');
        $item['data-app'] = $app->tab;
        $item['url']      = sprintf($link, $execution->id);

        if($execution->type == 'kanban') $item['url'] = helper::createLink('execution', 'kanban', "execution={$execution->id}");

        if(!isset($data[$group][$projectID])) $data[$group][$projectID] = $projectItem;
        $data[$group][$projectID]['items'][] = $item;
    }
}

/* 将分组数据转换为索引数组。Format grouped data to indexed array. */
foreach ($data as $key => $value) $data[$key] = array_values($value);

/**
 * 定义每个分组名称信息，包括可展开的已关闭分组。
 * Define every group name, include expanded group.
 */
$tabs = array();
$tabs[] = array('name' => 'my',     'text' => $lang->execution->involved);
$tabs[] = array('name' => 'other',  'text' => $lang->execution->other);
$tabs[] = array('name' => 'closed', 'text' => $lang->execution->closedExecution);

/**
 * 定义最终的 JSON 数据。
 * Define the final json data.
 */
$json = array();
$json['data']       = $data;
$json['tabs']       = $tabs;
$json['searchHint'] = $lang->searchAB;
$json['labelMap']   = array('project' => $lang->project->common);
$json['expandName'] = 'closed';
$json['itemType']   = 'execution';

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render json data to string and send to client.
 */
renderJson($json);
