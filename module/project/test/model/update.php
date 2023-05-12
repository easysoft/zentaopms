#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

/**

title=测试 projectModel::update();
cid=1
pid=1

正常更新项目的情况 >> 测试更新项目十
未关联产品的项目 >> 最少关联一个产品
更新项目名称为空时 >> 『项目名称』不能为空。
当计划完成为空时更新项目信息 >> 『计划开始』不能为空。
当项目的完成日期小于执行的完成日期时 >> 项目的完成日期应大于等于执行的最大完成日期：2022-10-26
当项目的开始日期大于执行的开始日期时 >> 项目的开始日期应小于等于执行的最小开始日期：2022-07-07

*/

global $tester;
$tester->app->loadConfig('execution');

$project = new Project();

$data = new stdclass();
$data->parent     = '0';
$data->name       = '测试更新项目十';
$data->begin      = '2022-07-06';
$data->end        = '2022-10-26';
$data->acl        = 'private';
$data->budget     = '100';
$data->budgetUnit = 'CNY';
$data->hasProduct = 1;
$data->PM         = 'admin';
$data->whitelist  = 'dev10, dev12';

$oldProject = new stdclass();
$oldProject->id        = 1;
$oldProject->stageBy   = 'project';
$oldProject->whitelist = 'dev1, dev2';

$uid = 144312345125125;

$normalProject = json_decode(json_encode($data));

$noProductProject = json_decode(json_encode($data));
$noProductProject->products = array();

$emptyTitleProject = json_decode(json_encode($data));
$emptyTitleProject->name = '';

$emptyBeginProject = json_decode(json_encode($data));
$emptyBeginProject->begin = '';

$emptyEndProject = json_decode(json_encode($data));
$emptyEndProject->end = '';

$beginGtExecutionBegin = json_decode(json_encode($data));
$beginGtExecutionBegin->begin = '2022-08-07';

$withoutProductProject = json_decode(json_encode($data));
$withoutProductProject->hasProduct = '';
$withoutProductProject->name       = '测试更新影子产品';
$withoutProductProject->status     = 'closed';

r($project->update($normalProject, $oldProject, $uid))         && p('name')             && e('测试更新项目十');                                         // 正常更新项目的情况
r($project->update($noProductProject, $oldProject, $uid))      && p('message:0')        && e('最少关联一个产品');                                       // 未关联产品的项目
r($project->update($emptyTitleProject, $oldProject, $uid))     && p('message[name]:0')  && e('『项目名称』不能为空。');                                 // 更新项目名称为空时
r($project->update($emptyBeginProject, $oldProject, $uid))     && p('message[begin]:0') && e('『计划开始』不能为空。');                                 // 当计划完成为空时更新项目信息
r($project->update($emptyEndProject, $oldProject, $uid))       && p('message:end')      && e('项目的完成日期应大于等于执行的最大完成日期：2022-10-26'); // 当项目的完成日期小于执行的完成日期时
r($project->update($beginGtExecutionBegin, $oldProject, $uid)) && p('message:begin')    && e('项目的开始日期应小于等于执行的最小开始日期：2022-07-07'); // 当项目的开始日期大于执行的开始日期时
r($project->update($withoutProductProject, $oldProject, $uid)) && p('name')             && e('测试更新影子产品');                                       // 无产品项目
