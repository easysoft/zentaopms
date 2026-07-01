#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 myModel::getFlowPairs();
timeout=0
cid=17284

- 测试步骤1：正常获取自定义工作流程键值对 @flow1:流程1,flow2:流程2,flow3:流程3

- 测试步骤2：内置工作流不影响非内置工作流的返回 @flow1:流程1,flow2:流程2,flow3:流程3

- 测试步骤3：数据持久性验证 @flow1:流程1,flow2:流程2,flow3:流程3

- 测试步骤4：方法返回结果的稳定性 @flow1:流程1,flow2:流程2,flow3:流程3

- 测试步骤5：不同用户权限下的数据访问 @flow1:流程1,flow2:流程2,flow3:流程3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('workflow')->gen(0);

$workflow = zenData('workflow');
$workflow->app->range('app1,app2,app3,app4,app5,app6');
$workflow->module->range('flow1,flow2,flow3,flow4,flow5,flow6');
$workflow->type->range('flow,flow,flow,flow,table,flow');
$workflow->name->range('流程1,流程2,流程3,流程4,流程5,流程6');
$workflow->status->range('normal,normal,normal,wait,normal,normal');
$workflow->buildin->range('0,0,1,0,0,0');
$workflow->gen(6);

global $tester;
$tester->dao->delete()->from(TABLE_WORKFLOWFIELD)->exec();

foreach(array(
    array('module' => 'flow1', 'field' => 'assignedTo', 'name' => '指派给', 'control' => 'select'),
    array('module' => 'flow2', 'field' => 'assignedTo', 'name' => '指派给', 'control' => 'select'),
    array('module' => 'flow3', 'field' => 'assignedTo', 'name' => '指派给', 'control' => 'select'),
    array('module' => 'flow4', 'field' => 'assignedTo', 'name' => '指派给', 'control' => 'select'),
    array('module' => 'flow6', 'field' => 'title',      'name' => '标题',   'control' => 'input'),
) as $field)
{
    $tester->dao->insert(TABLE_WORKFLOWFIELD)->data((object)$field)->exec();
}

$my = new myModelTest();

su('admin');
r($my->getFlowPairsTest()) && p() && e('flow1:流程1,flow2:流程2'); // 测试步骤1：正常获取自定义工作流程键值对
r($my->getFlowPairsTest()) && p() && e('flow1:流程1,flow2:流程2'); // 测试步骤2：内置工作流不影响非内置工作流的返回

// 3. 测试步骤3：测试数据持久性
r($my->getFlowPairsTest()) && p() && e('flow1:流程1,flow2:流程2'); // 测试步骤3：数据持久性验证

// 4. 测试步骤4：测试方法的稳定性
r($my->getFlowPairsTest()) && p() && e('flow1:流程1,flow2:流程2'); // 测试步骤4：方法返回结果的稳定性

// 5. 测试步骤5：测试用户权限验证（不同用户权限下的数据访问）
su('user');
r($my->getFlowPairsTest()) && p() && e('flow1:流程1,flow2:流程2'); // 测试步骤5：不同用户权限下的数据访问
