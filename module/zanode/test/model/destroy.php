#!/usr/bin/env php
<?php

/**

title=测试 zanodeModel::destroy();
timeout=0
cid=19825

- 执行zanodeTest模块的destroyTest方法，参数是1  @没有发现Agent服务
- 执行zanodeTest模块的destroyTest方法，参数是6  @success
- 执行zanodeTest模块的destroyTest方法，参数是999  @0
- 执行zanodeTest模块的destroyTest方法  @0
- 执行zanodeTest模块的destroyTest方法，参数是-1  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

$table = zenData('host');
$table->id->range('1-10');
$table->name->range('node{1-5}, physics{1-5}');
$table->type->range('node{10}');
$table->hostType->range('"{5}, physics{5}');
$table->status->range('running{5}, online{5}');
$table->osName->range('linux{5}, windows{5}');
$table->parent->range('1{5}, 0{5}');
$table->extranet->range('192.168.1.101-110');
$table->zap->range('8080{10}');
$table->tokenSN->range('token123{10}');
$table->deleted->range('0{10}');
$table->gen(10);

su('admin');

$zanodeTest = new zanodeTest();

r($zanodeTest->destroyTest(1)) && p() && e('没有发现Agent服务');
r($zanodeTest->destroyTest(6)) && p() && e('success');
r($zanodeTest->destroyTest(999)) && p() && e('0');
r($zanodeTest->destroyTest(0)) && p() && e('0');
r($zanodeTest->destroyTest(-1)) && p() && e('0');