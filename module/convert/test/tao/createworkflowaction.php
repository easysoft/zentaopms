#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createWorkflowAction();
timeout=0
cid=15853

- 没有参数时创建的工作流动作 @0
- 正确的接口参数创建后返回的relations第zentaoObject条的10000属性 @task
- 查询创建后的动作模块属性module @task
- 查询创建后的动作字段属性action @taskaction10001
- 查询创建后的动作名称属性name @动作A

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$workflow = zenData('workflow');
$workflow->module->range('task');
$workflow->gen(1);
zenData('workflowaction')->gen(0);

$convertTest = new convertTaoTest();

r($convertTest->createWorkflowActionTest(array(), array())) && p() && e('0'); // 没有参数时创建的工作流动作

$_SESSION['jiraMethod'] = 'api';
$relations = array();
$relations['zentaoObject']['10000'] = 'task';
$relations['zentaoAction10000']['10001'] = 'add_action';

$actions = array();
$actions['10000']['actions']['10001'] = array('name' => '动作A', 'to' => 'finish');
r($convertTest->createWorkflowActionTest($relations, $actions)) && p('zentaoObject:10000') && e('task'); // 正确的接口参数创建后返回的relations

global $tester;
r($tester->loadModel('workflowaction')->fetchByID(1)) && p('module') && e('task');            // 查询创建后的动作模块
r($tester->loadModel('workflowaction')->fetchByID(1)) && p('action') && e('taskaction10001'); // 查询创建后的动作字段
r($tester->loadModel('workflowaction')->fetchByID(1)) && p('name')   && e('动作A');           // 查询创建后的动作名称
