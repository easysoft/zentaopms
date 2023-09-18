#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

$project = zdTable('project');
$project->id->range('1-3');
$project->type->range('project');
$project->realBegan->range('20220427 000000:1w')->type('timestamp')->format('YYYY-MM-DD');
$project->gen(3);

/**

title=测试 projectTao::doClosed();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');

$project =  new stdClass;
$project->status  = 'closed';
$project->realEnd = '2023-04-28';

$oldProject1 = $tester->project->getByID(1);
$oldProject2 = $tester->project->getByID(2);
$oldProject3 = $tester->project->getByID(3);

r($tester->project->doClosed(1, $project, $oldProject1)) && p() && e('1'); #测试项目关闭时间realEnd小于今天 返回值1
r($tester->project->doClosed(2, $project, $oldProject2)) && p() && e('1'); #测试项目关闭时间realEnd大于项目开始时间realBegin 返回值1
r($tester->project->doClosed(3, $project, $oldProject3)) && p() && e('1'); #测试项目关闭必填字段不为空
