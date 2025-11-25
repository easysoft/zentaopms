#!/usr/bin/env php
<?php

/**

title=测试 buildZen::buildBuildForCreate();
timeout=0
cid=15518

- 执行buildTest模块的buildBuildForCreateTest方法
 - 属性name @Build 1.0
 - 属性builder @admin
- 执行buildTest模块的buildBuildForCreateTest方法
 - 属性name @Build 2.0
 - 属性execution @0
- 执行buildTest模块的buildBuildForCreateTest方法 属性name @Build 3.0
- 执行buildTest模块的buildBuildForCreateTest方法 属性createdBy @admin
- 执行buildTest模块的buildBuildForCreateTest方法 属性branch @1,2
- 执行buildTest模块的buildBuildForCreateTest方法 属性builds @1,2,3
- 执行buildTest模块的buildBuildForCreateTest方法 属性product @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->gen(5);
zenData('project')->gen(5);
zenData('build')->gen(0);
zenData('system')->gen(5);
zenData('user')->gen(5);

global $tester;
$tester->app->user = new stdclass();
$tester->app->user->account = 'admin';
$tester->app->tab = 'project';

su('admin');

$buildTest = new buildZenTest();

// 测试步骤1: 正常创建版本,使用已有系统
$_POST = array(
    'product'     => '1',
    'execution'   => '1',
    'name'        => 'Build 1.0',
    'builder'     => 'admin',
    'date'        => '2024-01-01',
    'system'      => '1',
    'isIntegrated' => 'no',
    'newSystem'   => ''
);
r($buildTest->buildBuildForCreateTest()) && p('name,builder') && e('Build 1.0,admin');

// 测试步骤2: isIntegrated为yes时,execution字段非必填
$_POST = array(
    'product'     => '1',
    'execution'   => '',
    'name'        => 'Build 2.0',
    'builder'     => 'admin',
    'date'        => '2024-01-02',
    'system'      => '1',
    'isIntegrated' => 'yes',
    'newSystem'   => ''
);
r($buildTest->buildBuildForCreateTest()) && p('name,execution') && e('Build 2.0,0');

// 测试步骤3: newSystem时,创建新系统并返回systemID
$_POST = array(
    'product'     => '1',
    'execution'   => '1',
    'name'        => 'Build 3.0',
    'builder'     => 'admin',
    'date'        => '2024-01-03',
    'system'      => '',
    'systemName'  => '新建系统',
    'isIntegrated' => 'no',
    'newSystem'   => '1'
);
r($buildTest->buildBuildForCreateTest()) && p('name') && e('Build 3.0');

// 测试步骤4: 测试createdBy默认值设置
$_POST = array(
    'product'     => '1',
    'execution'   => '1',
    'name'        => 'Build 4.0',
    'builder'     => 'admin',
    'date'        => '2024-01-04',
    'system'      => '1',
    'isIntegrated' => 'no',
    'newSystem'   => ''
);
r($buildTest->buildBuildForCreateTest()) && p('createdBy') && e('admin');

// 测试步骤5: 测试branch字段为数组时的join过滤
$_POST = array(
    'product'     => '1',
    'execution'   => '1',
    'name'        => 'Build 5.0',
    'builder'     => 'admin',
    'date'        => '2024-01-05',
    'system'      => '1',
    'branch'      => array('1', '2'),
    'isIntegrated' => 'no',
    'newSystem'   => ''
);
r($buildTest->buildBuildForCreateTest()) && p('branch', '|') && e('1,2');

// 测试步骤6: 测试builds字段为数组时的join过滤
$_POST = array(
    'product'     => '1',
    'execution'   => '1',
    'name'        => 'Build 6.0',
    'builder'     => 'admin',
    'date'        => '2024-01-06',
    'system'      => '1',
    'builds'      => array('1', '2', '3'),
    'isIntegrated' => 'no',
    'newSystem'   => ''
);
r($buildTest->buildBuildForCreateTest()) && p('builds', '|') && e('1,2,3');

// 测试步骤7: 测试product字段正确设置
$_POST = array(
    'product'     => '1',
    'execution'   => '1',
    'name'        => 'Build 7.0',
    'builder'     => 'admin',
    'date'        => '2024-01-07',
    'system'      => '1',
    'isIntegrated' => 'no',
    'newSystem'   => ''
);
r($buildTest->buildBuildForCreateTest()) && p('product') && e('1');