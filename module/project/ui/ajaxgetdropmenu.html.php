<?php
declare(strict_types=1);
/**
 * The ajaxgetdropmenu view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     project
 * @version     $Id
 * @link        https://www.zentao.net
 */
namespace zin;
if(in_array("{$module}-{$method}", $config->index->oldPages))
{
    include '../view/ajaxgetdropmenu.html.php';
    return;
}

/**
 * 获取项目所属分组。
 * Get project group.
 *
 * @param object $project
 * @return string
 */
$getProjectGroup = function($project) use($involvedProjects): string
{
    if(isset($involvedProjects[$project->id]) && $project->status != 'closed') return 'my';
    if($project->status == 'closed') return 'closed';
    return 'other';
};

$projectNames = array();
foreach($projects as $programID => $programProjects)
{
    $projectNames = array_merge($projectNames, array_column($programProjects, 'name'));
}

$projectPinyinNames = common::convert2Pinyin($projectNames);

if($extra == 'selectmode')
{
    /**
     * 定义每个分组下的选项数据列表。
     * Define the grouped data list.
     */
    $data = array();

    /* 处理分组数据。Process grouped data. */
    foreach($projects as $programID => $programProjects)
    {
        $programID = isset($programs[$programID]) ? $programID : 0;

        if(empty($data[$programID]))
        {
            $programItem = array();
            $programItem['id']       = $programID;
            $programItem['text']     = isset($programs[$programID]) ? zget($programs, $programID) : $lang->project->emptyProgram;
            $programItem['subtitle'] = $lang->program->common;
            $programItem['items']    = array();

            $data[$programID] = $programItem;
        }
        else
        {
            $programItem = $data[$programID];
        }

        foreach($programProjects as $index => $project)
        {
            $item = array();
            $item['id']       = $project->id;
            $item['text']     = $project->name;
            $item['icon']     = $project->model == 'scrum' ? 'sprint' : $project->model;
            $item['keys']     = zget($projectPinyinNames, $project->name, '');
            $item['value']    = $project->id;

            $data[$programID]['items'][] = $item;
        }
    }

    $data = array_values($data);
    renderJson($data);
}
elseif(!empty($project->isTpl))
{
    /* 模板单独渲染。 */
    foreach($projects as $programID => $programProjects)
    {
        foreach($programProjects as $index => $project)
        {
            $item = array();
            $item['id']       = $project->id;
            $item['text']     = $project->name;
            $item['icon']     = $project->model == 'scrum' ? 'sprint' : $project->model;
            $item['keys']     = zget($projectPinyinNames, $project->name, '');
            $item['involved'] = isset($involvedProjects[$project->id]);
            $item['url']      = helper::createLink('project', 'execution', "status=undone&projectID={$project->id}");

            $data[] = $item;
        }
    }

    $json = array();
    $json['data']       = $data;
    $json['searchHint'] = $lang->searchAB;
    $json['itemType']   = 'project';

    renderJson($json);
}
else
{
    /**
     * 定义每个分组下的选项数据列表。
     * Define the grouped data list.
     */
    $data = array('my' => array(), 'other' => array(), 'closed' => array());

    /**
     * 定义高亮的分组标签。
     * Define the active group.
     */
    $activeGroup = '';

    /* 处理分组数据。Process grouped data. */
    foreach($projects as $programID => $programProjects)
    {
        $programID = isset($programs[$programID]) ? $programID : 0;

        $programItem = array();
        $programItem['id']    = $programID;
        $programItem['type']  = 'program';
        $programItem['text']  = isset($programs[$programID]) ? zget($programs, $programID) : $lang->project->emptyProgram;
        $programItem['items'] = array();

        if(!$programID) $programItem['label'] = '';

        foreach($programProjects as $index => $project)
        {
            $group = $getProjectGroup($project);

            $item = array();
            $item['id']       = $project->id;
            $item['text']     = $project->name;
            $item['icon']     = $project->model == 'scrum' ? 'sprint' : $project->model;
            $item['keys']     = zget($projectPinyinNames, $project->name, '');
            $item['involved'] = isset($involvedProjects[$project->id]);

            if($useLink == 1)
            {
                $item['url'] = sprintf($link, $project->id);
                if((empty($project->multiple) || $project->type == 'kanban' || $project->model == 'kanban') && strpos($link, 'ajaxSwitchBelong') === false) $item['url'] = helper::createLink('project', 'index', "projectID={$project->id}");
            }
            else
            {
                $item['url'] = '#';
            }

            if(empty($activeGroup) && $projectID == $project->id) $activeGroup = $group;

            if($config->systemMode == 'light' || $config->vision == 'lite')
            {
                $data[$group][] = $item;
            }
            else
            {
                if(!isset($data[$group][$programID])) $data[$group][$programID] = $programItem;
                $data[$group][$programID]['items'][] = $item;
            }
        }
    }

    /* 将分组数据转换为索引数组。Format grouped data to indexed array. */
    foreach($data as $key => $value) $data[$key] = array_values($value);

    /**
     * 定义每个分组名称信息，包括可展开的已关闭分组。
     * Define every group name, include expanded group.
     */
    $tabs = array();
    $tabs[] = array('name' => 'my',     'text' => $lang->project->mine, 'active' => $activeGroup === 'my');
    $tabs[] = array('name' => 'other',  'text' => $lang->project->other, 'active' => $activeGroup === 'other');
    $tabs[] = array('name' => 'closed', 'text' => $lang->project->closedProject);

    /**
     * 定义最终的 JSON 数据。
     * Define the final json data.
     */
    $json = array();
    $json['data']       = $data;
    $json['tabs']       = $tabs;
    $json['searchHint'] = $lang->searchAB;
    $json['labelMap']   = array('program' => $lang->program->common);
    $json['expandName'] = 'closed';
    $json['itemType']   = 'project';

    /**
     * 渲染 JSON 字符串并发送到客户端。
     * Render json data to string and send to client.
     */
    renderJson($json);
}
