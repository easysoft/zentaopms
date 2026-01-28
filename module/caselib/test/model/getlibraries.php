#!/usr/bin/env php
<?php

/**

title=测试 caselibModel::getLibraries();
timeout=0
cid=0

- 测试步骤1：有效用例库数据查询
 - 属性1 @用例库1
 - 属性2 @用例库2
 - 属性3 @用例库3
 - 属性4 @用例库4
 - 属性5 @用例库5
- 测试步骤2：验证查询结果的数量 @5
- 测试步骤3：验证特定用例库的名称属性3 @用例库3
- 测试步骤4：验证排序规则(按order_desc,id_desc)
 -  @5
 - 属性1 @4
 - 属性2 @3
 - 属性3 @2
 - 属性4 @1
- 测试步骤5：验证空结果处理 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$testsuite = zenData('testsuite');
$testsuite->id->range('1-10');
$testsuite->product->range('0{5},1{5}');
$testsuite->name->range('用例库1,用例库2,用例库3,用例库4,用例库5,产品测试套件1,产品测试套件2,产品测试套件3,产品测试套件4,产品测试套件5');
$testsuite->type->range('library{5},suite{5}');
$testsuite->deleted->range('0{8},1{2}');
$testsuite->order->range('10,20,30,40,50,60,70,80,90,100');
$testsuite->gen(10);

zenData('user')->gen(1);

su('admin');

$caselib = new caselibModelTest();

r($caselib->getLibrariesTest()) && p('1,2,3,4,5') && e('用例库1,用例库2,用例库3,用例库4,用例库5');  // 测试步骤1：有效用例库数据查询
r(count($caselib->getLibrariesTest())) && p() && e('5');                                             // 测试步骤2：验证查询结果的数量
r($caselib->getLibrariesTest()) && p('3') && e('用例库3');                                           // 测试步骤3：验证特定用例库的名称
r(array_keys($caselib->getLibrariesTest())) && p('0,1,2,3,4') && e('5,4,3,2,1');                   // 测试步骤4：验证排序规则(按order_desc,id_desc)

// 清空数据并创建无用例库的场景测试
zenData('testsuite')->gen(0);

$emptyTestsuite = zenData('testsuite');
$emptyTestsuite->id->range('1-3');
$emptyTestsuite->product->range('1{3}');
$emptyTestsuite->name->range('产品套件1,产品套件2,产品套件3');
$emptyTestsuite->type->range('suite{3}');
$emptyTestsuite->deleted->range('0{3}');
$emptyTestsuite->gen(3);

$emptyCaselib = new caselibModelTest();
r($emptyCaselib->getLibrariesTest()) && p() && e('0');                                               // 测试步骤5：验证空结果处理