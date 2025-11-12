#!/usr/bin/env php
<?php

/**

title=测试 productZen::buildProductForCreate();
timeout=0
cid=0

- 执行productTest模块的buildProductForCreateTest方法
 - 属性name @Test Product 1
 - 属性PO @admin
 - 属性status @normal
- 执行productTest模块的buildProductForCreateTest方法，参数是1
 - 属性name @Test Product 2
 - 属性PO @admin
 - 属性status @normal
 - 属性type @branch
- 执行productTest模块的buildProductForCreateTest方法
 - 属性name @Test Product 3
 - 属性vision @rnd
- 执行productTest模块的buildProductForCreateTest方法
 - 属性name @Test Product 4
 - 属性acl @open
 - 属性whitelist @~~
- 执行productTest模块的buildProductForCreateTest方法
 - 属性name @Test Product 5
 - 属性acl @private
 - 属性whitelist @user1,user2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

// 测试步骤1:使用默认workflowGroup参数0创建产品数据
$_POST['name'] = 'Test Product 1';
$_POST['PO'] = 'admin';
$_POST['type'] = 'normal';
$_POST['acl'] = 'open';
$_POST['desc'] = 'Test description';
$_POST['status'] = 'normal';
r($productTest->buildProductForCreateTest(0)) && p('name,PO,status') && e('Test Product 1,admin,normal');

// 测试步骤2:使用workflowGroup参数1创建产品数据
$_POST['name'] = 'Test Product 2';
$_POST['PO'] = 'admin';
$_POST['type'] = 'branch';
$_POST['acl'] = 'private';
$_POST['desc'] = 'Test description 2';
$_POST['status'] = 'normal';
r($productTest->buildProductForCreateTest(1)) && p('name,PO,status,type') && e('Test Product 2,admin,normal,branch');

// 测试步骤3:验证默认情况下vision字段应存在
$_POST['name'] = 'Test Product 3';
$_POST['PO'] = 'admin';
$_POST['type'] = 'normal';
$_POST['acl'] = 'open';
$_POST['status'] = 'normal';
r($productTest->buildProductForCreateTest(0)) && p('name,vision') && e('Test Product 3,rnd');

// 测试步骤4:测试acl为open时whitelist字段为空
$_POST['name'] = 'Test Product 4';
$_POST['PO'] = 'admin';
$_POST['acl'] = 'open';
$_POST['whitelist'] = 'user1,user2';
$_POST['status'] = 'normal';
r($productTest->buildProductForCreateTest(0)) && p('name,acl,whitelist') && e('Test Product 4,open,~~');

// 测试步骤5:测试acl为private时whitelist字段保留
$_POST['name'] = 'Test Product 5';
$_POST['PO'] = 'admin';
$_POST['acl'] = 'private';
$_POST['whitelist'] = array('user1', 'user2');
$_POST['status'] = 'normal';
r($productTest->buildProductForCreateTest(0)) && p('name;acl;whitelist', ';') && e('Test Product 5;private;user1,user2');