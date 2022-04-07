#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';
su('admin');

/**

title=测试taskModel->batchUpdate();
cid=1
pid=1

*/

$project = new Project();
$projectIdList = array(11, 12, 13);

$data['names']         = array(11 => '批量修改项目11', 12 => '批量修改项目12', 13 => '批量修改项目13');
$data['projectIdList'] = $projectIdList;
$data['PMs']           = array(11 => 'admin', 12 => '', 13 => '');
$data['begins']        = array(11 => '2022-01-03', 12 => '2022-03-05', 13 => '');
$data['ends']          = array(11 => '2022-03-03', 12 => '2022-06-05', 13 => '2022-07-11');

$name     = array( '7' => '批量修改任务一');
$type     = array( '7' => 'devel');
$statuses = array('7' =>'doing');

$normal = array('names' => $name, 'types' => $type,'statuses'=> $statuses);

$task = new taskTest();
r($task->batchUpdateObject($normal, $taskID)) && p('1:field,old,new') && e('name,开发任务17,批量修改任务一'); // 测试批量修改任务
system("./ztest init");
