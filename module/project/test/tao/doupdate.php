#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

$project = zenData('project');
$project->id->range('1-5');
$project->project->range('0');
$project->name->prefix("项目")->range('1-5');
$project->code->prefix("project")->range('1-5');
$project->model->range("scrum,waterfall,kanban");
$project->auth->range("[]");
$project->path->range("[]");
$project->type->range("project");
$project->grade->range("1");
$project->days->range("1");
$project->status->range("wait");
$project->desc->range("[]");
$project->budget->range("100000,200000");
$project->budgetUnit->range("CNY");
$project->percent->range("0-0");
$project->gen(5);

$product = zenData('product')->gen(5);

/**

title=测试 projectTao::doUpdate();
timeout=0
cid=17902

*/

global $tester;
$project = new projectTaoTest();

$data = new stdclass();
$data->parent     = '0';
$data->name       = '测试更新项目十';
$data->begin      = '2022-07-06';
$data->end        = '2022-10-26';
$data->acl        = 'private';
$data->budget     = '100';
$data->model      = 'scrum';
$data->budgetUnit = 'CNY';
$data->hasProduct = 1;
$data->PM         = 'admin';
$data->whitelist  = 'dev10, dev12';

$normalProject = clone $data;

$emptyTitleProject = clone $data;
$emptyTitleProject->name = '';

$emptyBeginProject = clone $data;
$emptyBeginProject->begin = null;

$emptyEndProject = clone $data;
$emptyEndProject->end = '';

$beginGtExecutionBegin = clone $data;
$beginGtExecutionBegin->begin = '2022-08-07';

$noProductProject = clone $data;
$noProductProject->hasProduct = 0;
$noProductProject->name       = '测试更新影子产品';
$noProductProject->status     = 'closed';

r($project->doUpdateTest(1, $normalProject))         && p('name')   && e('测试更新项目十');                       // 正常更新项目的情况
r($project->doUpdateTest(1, $emptyTitleProject))     && p('name')   && e('~~');                                   // 更新项目名称为空时
r($project->doUpdateTest(1, $emptyBeginProject))     && p('finish') && e('~~');                                   // 当计划完成为空时更新项目信息
r($project->doUpdateTest(1, $emptyEndProject))       && p('end:0')  && e('『计划完成』应当大于『2022-07-06』。'); // 当项目的完成日期小于执行的完成日期时
r($project->doUpdateTest(1, $beginGtExecutionBegin)) && p('begin')  && e('2022-08-07');                           // 当项目的开始日期大于执行的开始日期时
r($project->doUpdateTest(1, $noProductProject))      && p('name')   && e('测试更新影子产品');                     // 无产品项目

r($project->doUpdateTest(2, $normalProject))         && p('name')   && e('测试更新项目十');                       // 正常更新项目的情况
r($project->doUpdateTest(2, $emptyTitleProject))     && p('name')   && e('~~');                                   // 更新项目名称为空时
r($project->doUpdateTest(2, $emptyBeginProject))     && p('finish') && e('~~');                                   // 当计划完成为空时更新项目信息
r($project->doUpdateTest(2, $emptyEndProject))       && p('end:0')  && e('『计划完成』应当大于『2022-07-06』。'); // 当项目的完成日期小于执行的完成日期时
r($project->doUpdateTest(2, $beginGtExecutionBegin)) && p('begin')  && e('2022-08-07');                           // 当项目的开始日期大于执行的开始日期时
r($project->doUpdateTest(2, $noProductProject))      && p('name:0') && e('『项目名称』已经有『测试更新影子产品』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');              // 无产品项目
