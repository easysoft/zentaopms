#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildBugsForBatchCreate();
timeout=0
cid=0

- 测试步骤1:正常批量创建2个bug属性count @2
- 测试步骤2:验证bug数据自动设置了创建人第0条的openedBy属性 @admin
- 测试步骤3:验证bug的product被正确设置第0条的product属性 @1
- 测试步骤4:验证module字段被正确设置第0条的module属性 @1
- 测试步骤5:验证openedBuild字段被正确设置第0条的openedBuild属性 @trunk
- 测试步骤6:验证不同产品ID被正确设置第0条的product属性 @2
- 测试步骤7:验证批量数据正确解析第0条的title属性 @正确的标题

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('product')->loadYaml('buildbugsforbatchcreate/product', false, 2)->gen(3);
zendata('module')->loadYaml('buildbugsforbatchcreate/module', false, 2)->gen(10);

su('admin');

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'batchcreate';

$bugTest = new bugZenTest();

// 准备测试数据
$_POST['title'] = array('测试Bug1', '测试Bug2');
$_POST['module'] = array(1, 4);
$_POST['openedBuild'] = array(array('trunk'), array('trunk'));
$_POST['steps'] = array("步骤1\n步骤2", "步骤A\n步骤B");
$_POST['pri'] = array(3, 2);
$_POST['severity'] = array(2, 3);
$_POST['type'] = array('codeerror', 'designdefect');

r($bugTest->buildBugsForBatchCreateTest(1, '0', array())) && p('count') && e('2'); // 测试步骤1:正常批量创建2个bug
r($bugTest->buildBugsForBatchCreateTest(1, '0', array())) && p('0:openedBy') && e('admin'); // 测试步骤2:验证bug数据自动设置了创建人
r($bugTest->buildBugsForBatchCreateTest(1, '0', array())) && p('0:product') && e('1'); // 测试步骤3:验证bug的product被正确设置
r($bugTest->buildBugsForBatchCreateTest(1, '0', array())) && p('0:module') && e('1'); // 测试步骤4:验证module字段被正确设置
r($bugTest->buildBugsForBatchCreateTest(1, '0', array())) && p('0:openedBuild') && e('trunk'); // 测试步骤5:验证openedBuild字段被正确设置

// 测试productID设置
$_POST['title'] = array('测试Bug3');
$_POST['module'] = array(1);
$_POST['openedBuild'] = array(array('trunk'));
$_POST['steps'] = array("步骤X");
$_POST['pri'] = array(3);
$_POST['severity'] = array(2);
$_POST['type'] = array('codeerror');
r($bugTest->buildBugsForBatchCreateTest(2, '0', array())) && p('0:product') && e('2'); // 测试步骤6:验证不同产品ID被正确设置

// 测试title字段
$_POST['title'] = array('正确的标题');
$_POST['module'] = array(5);
$_POST['openedBuild'] = array(array('trunk'));
$_POST['steps'] = array("测试步骤");
$_POST['pri'] = array(1);
$_POST['severity'] = array(1);
$_POST['type'] = array('codeerror');
r($bugTest->buildBugsForBatchCreateTest(1, '0', array())) && p('0:title') && e('正确的标题'); // 测试步骤7:验证批量数据正确解析