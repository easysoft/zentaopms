#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createWorkflow();
timeout=0
cid=0

- 执行convertTest模块的createWorkflowTest方法，参数是array 第zentaoObject条的10001属性 @add_custom
- 执行convertTest模块的createWorkflowTest方法，参数是array 第zentaoObject条的10002属性 @add_custom
- 执行convertTest模块的createWorkflowTest方法，参数是array  @0
- 执行convertTest模块的createWorkflowTest方法，参数是array 第zentaoObject条的10003属性 @existing_module
- 执行convertTest模块的createWorkflowTest方法，参数是array 第zentaoObject条的10004属性 @add_custom

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

$workflow = zenData('workflow');
$workflow->id->range('1-5');
$workflow->module->range('testmodule1,testmodule2,testmodule3,jira10001,jira10002');
$workflow->name->range('Test Workflow 1,Test Workflow 2,Test Workflow 3,Jira Task,Jira Bug');
$workflow->type->range('flow{5}');
$workflow->status->range('wait{3},active{2}');
$workflow->gen(0);

su('admin');

$convertTest = new convertTest();

r($convertTest->createWorkflowTest(array('zentaoObject' => array('10001' => 'add_custom')), array('actions' => array('1' => array('name' => 'Create Issue'))), array(), array())) && p('zentaoObject:10001') && e('add_custom');
r($convertTest->createWorkflowTest(array('zentaoObject' => array('10002' => 'add_custom')), array(), array(), array())) && p('zentaoObject:10002') && e('add_custom');
r($convertTest->createWorkflowTest(array(), array(), array(), array())) && p() && e('0');
r($convertTest->createWorkflowTest(array('zentaoObject' => array('10003' => 'existing_module')), array(), array(), array())) && p('zentaoObject:10003') && e('existing_module');
r($convertTest->createWorkflowTest(array('zentaoObject' => array('10004' => 'add_custom')), array('actions' => array('1' => array('name' => 'Test Action'))), array('1' => array('name' => 'Fixed')), array('1' => array('name' => 'High')))) && p('zentaoObject:10004') && e('add_custom');