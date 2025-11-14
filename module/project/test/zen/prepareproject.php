#!/usr/bin/env php
<?php

/**

title=测试 projectZen::prepareProject();
timeout=0
cid=17952

- 步骤1属性team @Test Project 1
- 步骤2属性team @Test Project 2
- 步骤3
 - 属性end @2059-12-31
 - 属性days @0
- 步骤4属性budget @0
- 步骤5属性products[0] @最少关联一个产品
- 步骤6属性days @可用工作日不能超过『10』天
- 步骤7属性budget @1234.57

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('project')->gen(5);
zenData('product')->gen(5);
zenData('branch')->gen(5);

su('admin');

global $tester, $app;
$app->rawModule = 'project';
$app->rawMethod = 'edit';

$projectTest = new projectZenTest();

// 步骤1:正常情况-有产品的项目
$_POST = array();
$_POST['name'] = 'Test Project 1';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-12-31';
$_POST['days'] = 200;
$_POST['parent'] = 1;
$_POST['products'] = array(1);
$_POST['branch'] = array(array(0));
$_POST['longTime'] = false;
$_POST['delta'] = '100';
$_POST['future'] = false;
$_POST['budget'] = 1000;
$_POST['whitelist'] = array();
$_POST['auth'] = array();
$_POST['storyType'] = array();
$formData1 = form::data($tester->config->project->form->edit);
r($projectTest->prepareProjectTest($formData1, 1)) && p('team') && e('Test Project 1'); // 步骤1

// 步骤2:无产品的项目
$_POST = array();
$_POST['name'] = 'Test Project 2';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-06-30';
$_POST['days'] = 100;
$_POST['parent'] = 1;
$_POST['longTime'] = false;
$_POST['delta'] = '50';
$_POST['future'] = false;
$_POST['budget'] = 500;
$_POST['whitelist'] = array();
$_POST['auth'] = array();
$_POST['storyType'] = array();
$formData2 = form::data($tester->config->project->form->edit);
r($projectTest->prepareProjectTest($formData2, 0)) && p('team') && e('Test Project 2'); // 步骤2

// 步骤3:长期项目
$_POST = array();
$_POST['name'] = 'Test Project 3';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-12-31';
$_POST['days'] = 200;
$_POST['parent'] = 1;
$_POST['longTime'] = true;
$_POST['delta'] = '999';
$_POST['future'] = false;
$_POST['budget'] = 0;
$_POST['whitelist'] = array();
$_POST['auth'] = array();
$_POST['storyType'] = array();
$formData3 = form::data($tester->config->project->form->edit);
r($projectTest->prepareProjectTest($formData3, 0)) && p('end,days') && e('2059-12-31,0'); // 步骤3

// 步骤4:预算为0时保持0
$_POST = array();
$_POST['name'] = 'Test Project 4';
$_POST['begin'] = '2025-01-01';
$_POST['end'] = '2025-12-31';
$_POST['days'] = 200;
$_POST['parent'] = 1;
$_POST['longTime'] = false;
$_POST['delta'] = '100';
$_POST['future'] = false;
$_POST['budget'] = 0;
$_POST['whitelist'] = array();
$_POST['auth'] = array();
$_POST['storyType'] = array();
$formData4 = form::data($tester->config->project->form->edit);
r($projectTest->prepareProjectTest($formData4, 0)) && p('budget') && e('0'); // 步骤4

// 步骤5:产品为空时返回错误
$_POST = array();
$_POST['name'] = 'Test Project 5';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-12-31';
$_POST['days'] = 200;
$_POST['parent'] = 1;
$_POST['longTime'] = false;
$_POST['delta'] = '100';
$_POST['future'] = false;
$_POST['budget'] = 0;
$_POST['products'] = array();
$_POST['branch'] = array();
$_POST['whitelist'] = array();
$_POST['auth'] = array();
$_POST['storyType'] = array();
$formData5 = form::data($tester->config->project->form->edit);
r($projectTest->prepareProjectTest($formData5, 1)) && p('products[0]') && e('最少关联一个产品'); // 步骤5

// 步骤6:工作日超出范围
$_POST = array();
$_POST['name'] = 'Test Project 6';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-01-10';
$_POST['days'] = 50;
$_POST['parent'] = 1;
$_POST['longTime'] = false;
$_POST['delta'] = '50';
$_POST['future'] = false;
$_POST['budget'] = 0;
$_POST['whitelist'] = array();
$_POST['auth'] = array();
$_POST['storyType'] = array();
$formData6 = form::data($tester->config->project->form->edit);
r($projectTest->prepareProjectTest($formData6, 0)) && p('days') && e('可用工作日不能超过『10』天'); // 步骤6

// 步骤7:预算四舍五入
$_POST = array();
$_POST['name'] = 'Test Project 7';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-12-31';
$_POST['days'] = 200;
$_POST['parent'] = 1;
$_POST['longTime'] = false;
$_POST['delta'] = '100';
$_POST['future'] = false;
$_POST['budget'] = 1234.567;
$_POST['whitelist'] = array();
$_POST['auth'] = array();
$_POST['storyType'] = array();
$formData7 = form::data($tester->config->project->form->edit);
r($projectTest->prepareProjectTest($formData7, 0)) && p('budget') && e('1234.57'); // 步骤7