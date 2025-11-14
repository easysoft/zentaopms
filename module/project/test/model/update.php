#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
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

title=测试 projectModel::update();
timeout=0
cid=17873

- 正常更新项目的情况属性name @测试更新项目十
- 更新项目名称为空时属性name @~~
- 当计划完成为空时更新项目信息属性finish @~~
- 当项目的完成日期小于执行的完成日期时第end条的0属性 @『计划完成』应当大于『2022-07-06』。
- 当项目的开始日期大于执行的开始日期时属性begin @2022-08-07
- 无产品项目属性name @测试更新影子产品
- 正常更新项目的情况属性name @测试更新项目十
- 更新项目名称为空时属性name @~~
- 当计划完成为空时更新项目信息属性finish @~~
- 当项目的完成日期小于执行的完成日期时第end条的0属性 @『计划完成』应当大于『2022-07-06』。
- 当项目的开始日期大于执行的开始日期时属性begin @2022-08-07
- 无产品项目第name条的0属性 @『项目名称』已经有『测试更新影子产品』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

*/

global $tester;
$project = new projectTest();

$_POST['uid'] = '0';

$data = new stdclass();
$data->parent        = '0';
$data->name          = '测试更新项目十';
$data->begin         = '2022-07-06';
$data->end           = '2022-10-26';
$data->acl           = 'private';
$data->model         = 'scrum';
$data->budget        = '100';
$data->budgetUnit    = 'CNY';
$data->hasProduct    = 1;
$data->PM            = 'admin';
$data->whitelist     = 'dev10, dev12';
$data->storyType     = 'story';
$data->workflowGroup = 0;

$normalProject = clone $data;

$emptyTitleProject = clone $data;
$emptyTitleProject->name = '';

$emptyBeginProject = clone $data;
$emptyBeginProject->begin = '0000-00-00';

$emptyEndProject = clone $data;
$emptyEndProject->end = '';

$beginGtExecutionBegin = clone $data;
$beginGtExecutionBegin->begin = '2022-08-07';

$noProductProject = clone $data;
$noProductProject->hasProduct = 0;
$noProductProject->name       = '测试更新影子产品';
$noProductProject->status     = 'closed';

$oldProject = $tester->loadModel('project')->getById(1);
r($project->updateTest($normalProject,         $oldProject)) && p('name')   && e('测试更新项目十');                       // 正常更新项目的情况
r($project->updateTest($emptyTitleProject,     $oldProject)) && p('name')   && e('~~');                                   // 更新项目名称为空时
r($project->updateTest($emptyBeginProject,     $oldProject)) && p('finish') && e('~~');                                   // 当计划完成为空时更新项目信息
r($project->updateTest($emptyEndProject,       $oldProject)) && p('end:0')  && e('『计划完成』应当大于『2022-07-06』。'); // 当项目的完成日期小于执行的完成日期时
r($project->updateTest($beginGtExecutionBegin, $oldProject)) && p('begin')  && e('2022-08-07');                           // 当项目的开始日期大于执行的开始日期时
r($project->updateTest($noProductProject,      $oldProject)) && p('name')   && e('测试更新影子产品');                     // 无产品项目

$oldProject = $tester->loadModel('project')->getById(2);
r($project->updateTest($normalProject,         $oldProject)) && p('name')   && e('测试更新项目十');                                                                                            // 正常更新项目的情况
r($project->updateTest($emptyTitleProject,     $oldProject)) && p('name')   && e('~~');                                                                                                        // 更新项目名称为空时
r($project->updateTest($emptyBeginProject,     $oldProject)) && p('finish') && e('~~');                                                                                                        // 当计划完成为空时更新项目信息
r($project->updateTest($emptyEndProject,       $oldProject)) && p('end:0')  && e('『计划完成』应当大于『2022-07-06』。');                                                                      // 当项目的完成日期小于执行的完成日期时
r($project->updateTest($beginGtExecutionBegin, $oldProject)) && p('begin')  && e('2022-08-07');                                                                                                // 当项目的开始日期大于执行的开始日期时
r($project->updateTest($noProductProject,      $oldProject)) && p('name:0') && e('『项目名称』已经有『测试更新影子产品』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 无产品项目
