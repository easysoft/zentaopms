#!/usr/bin/env php
<?php

/**

title=测试 executionZen::buildExecutionForCreate();
timeout=0
cid=0

- 执行executionzenTest模块的buildExecutionForCreateTest方法 属性project @所属项目不能为空。
- 执行executionzenTest模块的buildExecutionForCreateTest方法 属性openedBy @admin
- 执行executionzenTest模块的buildExecutionForCreateTest方法 属性parent @2
- 执行executionzenTest模块的buildExecutionForCreateTest方法 属性whitelist @
- 执行executionzenTest模块的buildExecutionForCreateTest方法 属性displayCards @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

zenData('project')->loadYaml('project_buildexecutionforcreate', false, 2)->gen(10);
zenData('user')->gen(5);

su('admin');

$executionzenTest = new executionZenTest();

unset($_POST);
r($executionzenTest->buildExecutionForCreateTest()) && p('project') && e('所属项目不能为空。');

$_POST['project'] = 1;
$_POST['name'] = '测试执行1';
$_POST['code'] = 'TEST001';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-06-30';
$_POST['acl'] = 'private';
$_POST['heightType'] = 'default';
$_POST['displayCards'] = 5;
r($executionzenTest->buildExecutionForCreateTest()) && p('openedBy') && e('admin');

$_POST['project'] = 2;
$_POST['name'] = '测试执行2';
$_POST['code'] = 'TEST002';
$_POST['parent'] = 2;
$_POST['begin'] = '2024-02-01';
$_POST['end'] = '2024-05-31';
$_POST['acl'] = 'private';
$_POST['heightType'] = 'default';
$_POST['displayCards'] = 5;
r($executionzenTest->buildExecutionForCreateTest()) && p('parent') && e('2');

$_POST['project'] = 3;
$_POST['name'] = '测试执行3';
$_POST['code'] = 'TEST003';
$_POST['begin'] = '2024-03-01';
$_POST['end'] = '2024-08-31';
$_POST['acl'] = 'open';
$_POST['heightType'] = 'default';
$_POST['displayCards'] = 5;
unset($_POST['parent']);
r($executionzenTest->buildExecutionForCreateTest()) && p('whitelist') && e('');

$_POST['project'] = 4;
$_POST['name'] = '测试执行4';
$_POST['code'] = 'TEST004';
$_POST['begin'] = '2024-04-01';
$_POST['end'] = '2024-09-30';
$_POST['acl'] = 'private';
$_POST['heightType'] = 'auto';
$_POST['displayCards'] = 10;
r($executionzenTest->buildExecutionForCreateTest()) && p('displayCards') && e('0');