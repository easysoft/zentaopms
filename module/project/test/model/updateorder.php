#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData()
{
    $project = zenData('project');
    $project->id->range('1-5');
    $project->gen(5);
}

/**

title=测试 projectModel::suspend;
timeout=0
cid=17875

- 检查更新 id_asc 排序的返回结果 @1
- 检查 id_asc 排序后, projectID 为 1 的 order @5
- 检查 id_asc 排序后, projectID 为 2 的 order @10
- 检查 id_asc 排序后, projectID 为 3 的 order @15
- 检查 id_asc 排序后, projectID 为 4 的 order @20
- 检查 id_asc 排序后, projectID 为 5 的 order @25
- 检查更新 id_desc 排序的返回结果 @1
- 检查 id_desc 排序后, projectID 为 1 的 order @25
- 检查 id_desc 排序后, projectID 为 2 的 order @20
- 检查 id_desc 排序后, projectID 为 3 的 order @15
- 检查 id_desc 排序后, projectID 为 4 的 order @10
- 检查 id_desc 排序后, projectID 为 5 的 order @5

*/

global $tester;
$tester->loadModel('project');

initData();

$idList  = array(1,2,3,4,5);
$order1  = 'id_asc';
$order2  = 'id_desc';
$project1 = $tester->project->updateOrder($idList, $order1);
r($project1) && p() && e('1'); //检查更新 id_asc 排序的返回结果

$projects = $tester->project->dao->select('id,`order`')->from(TABLE_PROJECT)->where('id')->in($idList)->orderBy('`order`')->fetchPairs('id', 'order');
r($projects[1]) && p() && e('5');  //检查 id_asc 排序后, projectID 为 1 的 order
r($projects[2]) && p() && e('10'); //检查 id_asc 排序后, projectID 为 2 的 order
r($projects[3]) && p() && e('15'); //检查 id_asc 排序后, projectID 为 3 的 order
r($projects[4]) && p() && e('20'); //检查 id_asc 排序后, projectID 为 4 的 order
r($projects[5]) && p() && e('25'); //检查 id_asc 排序后, projectID 为 5 的 order

$project2 = $tester->project->updateOrder($idList, $order2);
r($project2) && p() && e('1'); //检查更新 id_desc 排序的返回结果

$projects = $tester->project->dao->select('id,`order`')->from(TABLE_PROJECT)->where('id')->in($idList)->orderBy('`order`')->fetchPairs('id', 'order');
r($projects[1]) && p() && e('25'); //检查 id_desc 排序后, projectID 为 1 的 order
r($projects[2]) && p() && e('20'); //检查 id_desc 排序后, projectID 为 2 的 order
r($projects[3]) && p() && e('15'); //检查 id_desc 排序后, projectID 为 3 的 order
r($projects[4]) && p() && e('10'); //检查 id_desc 排序后, projectID 为 4 的 order
r($projects[5]) && p() && e('5');  //检查 id_desc 排序后, projectID 为 5 的 order