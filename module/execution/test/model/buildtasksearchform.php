#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$execution = zenData('project');
$execution->id->range('1-8');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3,阶段1,阶段2,阶段3');
$execution->type->range('project{2},sprint,stage,kanban,stage{3}');
$execution->model->range('scrum,waterfall,``{6}');
$execution->status->range('doing');
$execution->parent->range('0,0,1{3},2{3}');
$execution->project->range('0,0,1{3},2{3}');
$execution->grade->range('2{2},1{6}');
$execution->path->range('1,2,`1,3`,`1,4`,`1,5`,`2,6`,`2,7`,`2,8`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(8);

$task = zenData('task');
$task->id->range('1-10');
$task->name->range('1-10')->prefix('任务');
$task->execution->range('3-5');
$task->type->range('test,devel');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->consumed->range('1-10');
$task->gen(10);

$module = zenData('module');
$module->name->range('1,2,3,4,5')->prefix('模块');
$module->gen(5);

$module = zenData('module');
$module->name->range('1,2,3,4,5')->prefix('模块');
$module->gen(5);

su('admin');

/**

title=测试executionModel->buildTaskSearchForm();
timeout=0
cid=0

- module 为 projectTask，缓存查询参数，查询参数中 queryID 为空。 @0
- module 为 projectTask，缓存查询参数，查询参数中 actionURL 为空。 @0
- module 为 projectTask，缓存查询参数，查询参数中有 project 字段。 @1
- module 为 projectTask，缓存查询参数，查询参数中有 module 字段。 @1
- module 为 projectTask，缓存查询参数，打印 module 的值。属性module @task
- module 为 projectTask，缓存查询参数，打印所属执行列表。
 - 属性3 @0
 - 属性4 @0
 - 属性5 @0
- module 为 projectTask，不缓存查询参数，查询参数中 queryID 有值。 @1
- module 为 projectTask，不缓存查询参数，查询参数中 actionURL 有值。 @1
- module 为 projectTask，不缓存查询参数，查询参数中没有 project 字段。 @0
- module 为 projectTask，不缓存查询参数，查询参数中没有 module 字段。 @0
- module 为 projectTask，不缓存查询参数，打印 module 的值。属性module @projectTask
- module 为 projectTask，不缓存查询参数，打印 queryID 的值。属性queryID @1
- module 为 projectTask，不缓存查询参数，打印 actionURL 的值。属性actionURL @/project-execution-bySearch-1-order_desc-0-0-100-1-myQueryID.html
- module 为 projectTask，不缓存查询参数，打印所属执行列表。
 - 属性3 @迭代1
 - 属性4 @迭代2
 - 属性5 @迭代3
- module 为 projectTask，缓存查询参数，查询参数中 queryID 为空。 @0
- module 为 projectTask，缓存查询参数，查询参数中 actionURL 为空。 @0
- module 为 projectTask，缓存查询参数，查询参数中有 project 字段。 @1
- module 为 projectTask，缓存查询参数，打印 module 的值。属性module @task
- module 为 projectTask，缓存查询参数，打印所属执行列表。
 - 属性6 @0
 - 属性7 @0
 - 属性8 @0
- module 为 projectTask，不缓存查询参数，查询参数中 queryID 有值。 @1
- module 为 projectTask，不缓存查询参数，查询参数中 actionURL 有值。 @1
- module 为 projectTask，不缓存查询参数，查询参数中没有 project 字段。 @0
- module 为 projectTask，不缓存查询参数，打印 module 的值。属性module @projectTask
- module 为 projectTask，不缓存查询参数，打印 queryID 的值。属性queryID @1
- module 为 projectTask，不缓存查询参数，打印 actionURL 的值。属性actionURL @/programplan-browse-2-0-gantt-id_asc-0-bysearch-myQueryID.html
- module 为 projectTask，不缓存查询参数，打印所属执行列表。
 - 属性6 @/阶段1
 - 属性7 @/阶段2
 - 属性8 @/阶段3
- module 为 task，缓存查询参数，查询参数中 queryID 为空。 @0
- module 为 task，缓存查询参数，查询参数中 actionURL 为空。 @0
- module 为 task，缓存查询参数，查询参数中 onMenuBar 为空。 @0
- module 为 task，缓存查询参数，打印所属项目列表。
 - 属性1 @0
 - 属性2 @0
 - 属性all @0
- module 为 task，缓存查询参数，打印所属执行列表。
 -  @0
 - 属性3 @0
 - 属性all @0
- module 为 task，不缓存查询参数，查询参数中 queryID 有值。 @1
- module 为 task，不缓存查询参数，查询参数中 actionURL 有值。 @1
- module 为 task，不缓存查询参数，查询参数中 onMenuBar 有值。 @1
- module 为 task，不缓存查询参数，打印 module 的值。属性module @task
- module 为 task，不缓存查询参数，打印 queryID 的值。属性queryID @1
- module 为 task，不缓存查询参数，打印 actionURL 的值。属性actionURL @/execution-task-3-bySearch-myQueryID.html
- module 为 task，不缓存查询参数，打印所属项目列表。
 - 属性1 @项目1
 - 属性2 @项目2
 - 属性all @所有项目
- module 为 task，不缓存查询参数，打印所属执行列表。
 -  @~~
 - 属性3 @迭代1
 - 属性all @所有执行

*/

$execution = new executionModelTest();
$queryID   = 1;

/**
 * 测试为项目迭代列表页面构造搜索参数功能。
 */
$executionID = 1;
$executions  = [3 => '迭代1', 4 => '迭代2', 5 => '迭代3'];
$actionURL   = '/project-execution-bySearch-1-order_desc-0-0-100-1-myQueryID.html';
$module      = 'projectTask';
$searchConfig = $execution->buildTaskSearchFormTest($executionID, $executions, $queryID, $actionURL, $module, true);
r(isset($searchConfig['queryID']))                && p()         && e(0);       // module 为 projectTask，缓存查询参数，查询参数中 queryID 为空。
r(isset($searchConfig['actionURL']))              && p()         && e(0);       // module 为 projectTask，缓存查询参数，查询参数中 actionURL 为空。
r(isset($searchConfig['fields']['project']))      && p()         && e(1);       // module 为 projectTask，缓存查询参数，查询参数中有 project 字段。
r(isset($searchConfig['fields']['module']))       && p()         && e(1);       // module 为 projectTask，缓存查询参数，查询参数中有 module 字段。
r($searchConfig)                                  && p('module') && e('task');  // module 为 projectTask，缓存查询参数，打印 module 的值。
r($searchConfig['params']['execution']['values']) && p('3,4,5')  && e('0,0,0'); // module 为 projectTask，缓存查询参数，打印所属执行列表。

$searchConfig = $execution->buildTaskSearchFormTest($executionID, $executions, $queryID, $actionURL, $module, false);
r(isset($searchConfig['queryID']))                && p()            && e(1);                                                                   // module 为 projectTask，不缓存查询参数，查询参数中 queryID 有值。
r(isset($searchConfig['actionURL']))              && p()            && e(1);                                                                   // module 为 projectTask，不缓存查询参数，查询参数中 actionURL 有值。
r(isset($searchConfig['fields']['project']))      && p()            && e(0);                                                                   // module 为 projectTask，不缓存查询参数，查询参数中没有 project 字段。
r(isset($searchConfig['fields']['module']))       && p()            && e(0);                                                                   // module 为 projectTask，不缓存查询参数，查询参数中没有 module 字段。
r($searchConfig)                                  && p('module')    && e('projectTask');                                                       // module 为 projectTask，不缓存查询参数，打印 module 的值。
r($searchConfig)                                  && p('queryID')   && e('1');                                                                 // module 为 projectTask，不缓存查询参数，打印 queryID 的值。
r($searchConfig)                                  && p('actionURL') && e('/project-execution-bySearch-1-order_desc-0-0-100-1-myQueryID.html'); // module 为 projectTask，不缓存查询参数，打印 actionURL 的值。
r($searchConfig['params']['execution']['values']) && p('3,4,5')     && e('迭代1,迭代2,迭代3');                                                 // module 为 projectTask，不缓存查询参数，打印所属执行列表。

/**
 * 测试为瀑布项目阶段甘特图页面构造搜索参数功能。
 */
$executionID  = 2;
$executions   = [6 => '/阶段1', 7 => '/阶段2', 8 => '/阶段3'];
$actionURL    = '/programplan-browse-2-0-gantt-id_asc-0-bysearch-myQueryID.html';
$module       = 'projectTask';
$searchConfig = $execution->buildTaskSearchFormTest($executionID, $executions, $queryID, $actionURL, $module, true);
r(isset($searchConfig['queryID']))                && p()         && e(0);       // module 为 projectTask，缓存查询参数，查询参数中 queryID 为空。
r(isset($searchConfig['actionURL']))              && p()         && e(0);       // module 为 projectTask，缓存查询参数，查询参数中 actionURL 为空。
r(isset($searchConfig['fields']['project']))      && p()         && e(1);       // module 为 projectTask，缓存查询参数，查询参数中有 project 字段。
r($searchConfig)                                  && p('module') && e('task');  // module 为 projectTask，缓存查询参数，打印 module 的值。
r($searchConfig['params']['execution']['values']) && p('6,7,8')  && e('0,0,0'); // module 为 projectTask，缓存查询参数，打印所属执行列表。

$searchConfig = $execution->buildTaskSearchFormTest($executionID, $executions, $queryID, $actionURL, $module, false);
r(isset($searchConfig['queryID']))                && p()            && e(1);                                                                // module 为 projectTask，不缓存查询参数，查询参数中 queryID 有值。
r(isset($searchConfig['actionURL']))              && p()            && e(1);                                                                // module 为 projectTask，不缓存查询参数，查询参数中 actionURL 有值。
r(isset($searchConfig['fields']['project']))      && p()            && e(0);                                                                // module 为 projectTask，不缓存查询参数，查询参数中没有 project 字段。
r($searchConfig)                                  && p('module')    && e('projectTask');                                                // module 为 projectTask，不缓存查询参数，打印 module 的值。
r($searchConfig)                                  && p('queryID')   && e('1');                                                              // module 为 projectTask，不缓存查询参数，打印 queryID 的值。
r($searchConfig)                                  && p('actionURL') && e('/programplan-browse-2-0-gantt-id_asc-0-bysearch-myQueryID.html'); // module 为 projectTask，不缓存查询参数，打印 actionURL 的值。
r($searchConfig['params']['execution']['values']) && p('6,7,8')     && e('/阶段1,/阶段2,/阶段3');                                           // module 为 projectTask，不缓存查询参数，打印所属执行列表。

/**
 * 测试为执行任务列表页面构造搜索参数功能。
 */
$executionID  = 3;
$executions   = [3 => '迭代1', 4 => '迭代2', 5 => '迭代3'];
$actionURL    = '/execution-task-3-bySearch-myQueryID.html';
$module       = 'task';
$searchConfig = $execution->buildTaskSearchFormTest($executionID, $executions, $queryID, $actionURL, $module, true);
r(isset($searchConfig['queryID']))                && p()          && e(0);       // module 为 task，缓存查询参数，查询参数中 queryID 为空。
r(isset($searchConfig['actionURL']))              && p()          && e(0);       // module 为 task，缓存查询参数，查询参数中 actionURL 为空。
r(isset($searchConfig['onMenuBar']))              && p()          && e(0);       // module 为 task，缓存查询参数，查询参数中 onMenuBar 为空。
r($searchConfig['params']['project']['values'])   && p('1,2,all') && e('0,0,0'); // module 为 task，缓存查询参数，打印所属项目列表。
r($searchConfig['params']['execution']['values']) && p(',3,all')  && e('0,0,0'); // module 为 task，缓存查询参数，打印所属执行列表。

$searchConfig = $execution->buildTaskSearchFormTest($executionID, $executions, $queryID, $actionURL, $module, false);
r(isset($searchConfig['queryID']))                && p()            && e(1);                                           // module 为 task，不缓存查询参数，查询参数中 queryID 有值。
r(isset($searchConfig['actionURL']))              && p()            && e(1);                                           // module 为 task，不缓存查询参数，查询参数中 actionURL 有值。
r(isset($searchConfig['onMenuBar']))              && p()            && e(1);                                           // module 为 task，不缓存查询参数，查询参数中 onMenuBar 有值。
r($searchConfig)                                  && p('module')    && e('task');                                      // module 为 task，不缓存查询参数，打印 module 的值。
r($searchConfig)                                  && p('queryID')   && e('1');                                         // module 为 task，不缓存查询参数，打印 queryID 的值。
r($searchConfig)                                  && p('actionURL') && e('/execution-task-3-bySearch-myQueryID.html'); // module 为 task，不缓存查询参数，打印 actionURL 的值。
r($searchConfig['params']['project']['values'])   && p('1,2,all')   && e('项目1,项目2,所有项目');                      // module 为 task，不缓存查询参数，打印所属项目列表。
r($searchConfig['params']['execution']['values']) && p(',3,all')    && e('~~,迭代1,所有执行');                         // module 为 task，不缓存查询参数，打印所属执行列表。
