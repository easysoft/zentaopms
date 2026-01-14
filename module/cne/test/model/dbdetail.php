#!/usr/bin/env php
<?php

/**

title=测试 cneModel::dbDetail();
timeout=0
cid=15609

- 步骤1：测试空参数边界值 @0
- 步骤2：测试部分参数为空的情况 @0
- 步骤3：测试错误数据库服务名的异常处理 @0
- 步骤4：测试空数据库服务名但有效命名空间 @0
- 步骤5：测试错误命名空间的异常处理 @0
- 步骤6：测试正确参数获取host属性属性host @zentaopaas-mysql.quickon-system.svc
- 步骤7：测试正确参数获取username属性属性username @root

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$cneTest = new cneModelTest();

r($cneTest->dbDetailTest('', '')) && p() && e('0');                                                                      // 步骤1：测试空参数边界值
r($cneTest->dbDetailTest('mysql', '')) && p() && e('0');                                                                 // 步骤2：测试部分参数为空的情况
r($cneTest->dbDetailTest('mysql', 'quickon-system')) && p() && e('0');                                                  // 步骤3：测试错误数据库服务名的异常处理
r($cneTest->dbDetailTest('', 'quickon-system')) && p() && e('0');                                                       // 步骤4：测试空数据库服务名但有效命名空间
r($cneTest->dbDetailTest('zentaopaas-mysql', 'invalid-namespace')) && p() && e('0');                                    // 步骤5：测试错误命名空间的异常处理
r($cneTest->dbDetailTest('zentaopaas-mysql', 'quickon-system')) && p('host') && e('zentaopaas-mysql.quickon-system.svc'); // 步骤6：测试正确参数获取host属性
r($cneTest->dbDetailTest('zentaopaas-mysql', 'quickon-system')) && p('username') && e('root');                         // 步骤7：测试正确参数获取username属性