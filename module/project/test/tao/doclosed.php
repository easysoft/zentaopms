#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

$project = zenData('project');
$project->id->range('1-3');
$project->type->range('project');
$project->realBegan->range('20220427 000000:1w')->type('timestamp')->format('YYYY-MM-DD');
$project->gen(3);

/**

title=测试 projectTao::doClosed();
timeout=0
cid=17898

- #测试项目关闭时间realEnd小于今天 返回值1 @1
- #测试项目关闭时间realEnd大于项目开始时间realBegin 返回值0 @0
- 检查错误信息 @『实际完成日期』应当不小于实际开始时间『2023-04-29』。
- #测试项目关闭必填字段不为空 @0
- 检查错误信息 @『实际完成日期』不能为空。

*/

global $tester;
$tester->loadModel('project');

$project =  new stdClass;
$project->status  = 'closed';
$project->realEnd = '2023-04-28';

$oldProject1 = $tester->project->fetchByID(1);
$oldProject2 = $tester->project->fetchByID(2);
$oldProject3 = $tester->project->fetchByID(3);

r($tester->project->doClosed(1, $project, $oldProject1)) && p() && e('1'); #测试项目关闭时间realEnd小于今天 返回值1

$oldProject2->realBegan = '2023-04-29';
r($tester->project->doClosed(2, $project, $oldProject2)) && p() && e('0'); #测试项目关闭时间realEnd大于项目开始时间realBegin 返回值0
$errors = dao::getError();
r($errors['realEnd'][0]) && p() && e('『实际完成日期』应当不小于实际开始时间『2023-04-29』。'); //检查错误信息

$project->realEnd = '';
r($tester->project->doClosed(3, $project, $oldProject3)) && p() && e('0'); #测试项目关闭必填字段不为空
$errors = dao::getError();
r($errors['realEnd'][0]) && p() && e('『实际完成日期』不能为空。'); //检查错误信息