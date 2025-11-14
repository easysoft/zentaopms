#!/usr/bin/env php
<?php

/**

title=测试 docZen::buildLibForCreateLib();
timeout=0
cid=16183

- 测试创建产品类型文档库属性addedBy @admin
- 测试创建产品类型文档库并关联产品属性product @1
- 测试创建项目类型文档库并关联项目属性project @2
- 测试创建执行类型文档库属性addedBy @admin
- 测试创建API类型文档库属性addedBy @admin
- 测试创建自定义类型文档库属性addedBy @admin
- 测试创建我的空间类型文档库属性addedBy @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doczen.unittest.class.php';

global $app;
$app->rawModule = 'doc';
$app->rawMethod = 'createLib';

su('admin');

$docTest = new docZenTest();

$_POST['type'] = 'product';
$_POST['name'] = 'Product Lib';
r($docTest->buildLibForCreateLibTest()) && p('addedBy') && e('admin'); // 测试创建产品类型文档库

$_POST['type'] = 'product';
$_POST['product'] = 1;
$_POST['name'] = 'Product Lib 1';
r($docTest->buildLibForCreateLibTest()) && p('product') && e('1'); // 测试创建产品类型文档库并关联产品

$_POST['type'] = 'project';
$_POST['product'] = '';
$_POST['project'] = 2;
$_POST['name'] = 'Project Lib';
r($docTest->buildLibForCreateLibTest()) && p('project') && e('2'); // 测试创建项目类型文档库并关联项目

$_POST['type'] = 'execution';
$_POST['project'] = '';
$_POST['libType'] = 'lib';
$_POST['execution'] = 3;
$_POST['name'] = 'Execution Lib';
r($docTest->buildLibForCreateLibTest()) && p('addedBy') && e('admin'); // 测试创建执行类型文档库

$_POST['type'] = 'api';
$_POST['execution'] = '';
$_POST['libType'] = 'api';
$_POST['name'] = 'API Lib';
r($docTest->buildLibForCreateLibTest()) && p('addedBy') && e('admin'); // 测试创建API类型文档库

$_POST['type'] = 'custom';
$_POST['libType'] = '';
$_POST['name'] = 'Custom Lib';
r($docTest->buildLibForCreateLibTest()) && p('addedBy') && e('admin'); // 测试创建自定义类型文档库

$_POST['type'] = 'mine';
$_POST['name'] = 'Mine Lib';
r($docTest->buildLibForCreateLibTest()) && p('addedBy') && e('admin'); // 测试创建我的空间类型文档库