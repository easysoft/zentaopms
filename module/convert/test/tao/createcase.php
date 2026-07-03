#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createCase();
timeout=0
cid=15834

- 执行convertTest模块的createCaseTest方法，参数是1, 1, 1,   @1
- 执行convertTest模块的createCaseTest方法，参数是999, 1, 1, @1
- 执行convertTest模块的createCaseTest方法，参数是1, 1, 1,   @1
- 执行convertTest模块的createCaseTest方法，参数是1, 1, 1,   @1
- 执行convertTest模块的createCaseTest方法，参数是0, 0, 0,   @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->code->range('product1,product2,product3');
$product->status->range('normal{3}');
$product->gen(3);

$project = zenData('project');
$project->id->range('1-3');
$project->name->range('项目1,项目2,项目3');
$project->code->range('project1,project2,project3');
$project->status->range('wait{3}');
$project->type->range('project{3}');
$project->gen(3);

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin,user1,testuser');
$user->realname->range('管理员,用户1,测试用户');
$user->gen(3);

su('admin');

$convertTest = new convertTaoTest();

$relations = array(
    'zentaoFieldtestcase'  => array('jiraStage' => 'stage'),
    'zentaoStatustestcase' => array('open' => 'normal', 'closed' => 'normal')
);

$data1 = (object)array('id' => 1001, 'summary' => '正常测试用例', 'priority' => '2', 'issuestatus' => 'open',   'issuetype' => 'testcase', 'creator' => 'admin',    'created' => '2023-01-01 10:00:00', 'jiraStage' => 'unit');
$data2 = (object)array('id' => 1002, 'summary' => '无效产品ID测试', 'priority' => '3', 'issuestatus' => 'open',   'issuetype' => 'testcase', 'creator' => 'admin',    'created' => '2023-01-01 11:00:00', 'jiraStage' => 'system');
$data3 = (object)array('id' => 1003, 'summary' => '',           'priority' => '',  'issuestatus' => '',       'issuetype' => 'testcase', 'creator' => '',         'created' => '',                    'jiraStage' => 'interface');
$data4 = (object)array('id' => 1004, 'summary' => '缺少字段测试',   'priority' => '',  'issuestatus' => 'open',   'issuetype' => 'testcase', 'creator' => '',         'created' => '',                    'jiraStage' => 'api');
$data5 = (object)array('id' => 1005, 'summary' => '边界值测试',   'priority' => '1', 'issuestatus' => 'closed', 'issuetype' => 'testcase', 'creator' => 'testuser', 'created' => '2023-01-01 12:00:00', 'jiraStage' => 'security');

r($convertTest->createCaseTest(1,   1, 0, $data1, $relations)) && p() && e('1');
r($convertTest->createCaseTest(999, 1, 0, $data2, $relations)) && p() && e('1');
r($convertTest->createCaseTest(1,   1, 0, $data3, $relations)) && p() && e('1');
r($convertTest->createCaseTest(1,   2, 0, $data4, $relations)) && p() && e('1');
r($convertTest->createCaseTest(0,   0, 0, $data5, $relations)) && p() && e('1');
