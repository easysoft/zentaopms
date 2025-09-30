#!/usr/bin/env php
<?php

/**

title=测试 cneModel::dbDetail();
timeout=0
cid=0

- 测试步骤1：空的数据库服务名称和命名空间 @0
- 测试步骤2：错误的命名空间参数 @0
- 测试步骤3：错误的数据库服务名称 @0
- 测试步骤4：正确的参数获取数据库主机信息属性host @zentaopaas-mysql.quickon-system.svc
- 测试步骤5：正确的参数获取数据库用户信息属性username @root

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneModel  = new cneTest();

r($cneModel->dbDetailTest('', '')) && p() && e('0'); // 测试步骤1：空的数据库服务名称和命名空间
r($cneModel->dbDetailTest('mysql', '')) && p() && e('0'); // 测试步骤2：错误的命名空间参数
r($cneModel->dbDetailTest('mysql', 'quickon-system')) && p() && e('0'); // 测试步骤3：错误的数据库服务名称
r($cneModel->dbDetailTest('zentaopaas-mysql', 'quickon-system')) && p('host') && e('zentaopaas-mysql.quickon-system.svc'); // 测试步骤4：正确的参数获取数据库主机信息
r($cneModel->dbDetailTest('zentaopaas-mysql', 'quickon-system')) && p('username') && e('root'); // 测试步骤5：正确的参数获取数据库用户信息