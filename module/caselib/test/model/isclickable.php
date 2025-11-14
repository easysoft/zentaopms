#!/usr/bin/env php
<?php

/**

title=测试 caselibModel::isClickable();
timeout=0
cid=15535

- 执行caselibTest模块的isClickableTest方法，参数是$libObject, 'createcase'  @1
- 执行caselibTest模块的isClickableTest方法，参数是$libWithProduct, 'createcase'  @0
- 执行caselibTest模块的isClickableTest方法，参数是$emptyObject, 'createcase'  @0
- 执行caselibTest模块的isClickableTest方法，参数是$libObject, 'edit'  @1
- 执行caselibTest模块的isClickableTest方法，参数是$libObject, ''  @1
- 执行caselibTest模块的isClickableTest方法，参数是$libObject2, 'CREATECASE'  @1
- 执行caselibTest模块的isClickableTest方法，参数是$libObject, 'nonexistent'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

su('admin');

$caselibTest = new caselibTest();

// 测试步骤1：测试createcase操作且对象有lib无product时是否可点击
$libObject = new stdclass();
$libObject->lib = 1;
r($caselibTest->isClickableTest($libObject, 'createcase')) && p() && e('1');

// 测试步骤2：测试createcase操作且对象有lib有product时是否可点击
$libWithProduct = new stdclass();
$libWithProduct->lib = 1;
$libWithProduct->product = 1;
r($caselibTest->isClickableTest($libWithProduct, 'createcase')) && p() && e('0');

// 测试步骤3：测试createcase操作且对象无lib属性时是否可点击
$emptyObject = new stdclass();
r($caselibTest->isClickableTest($emptyObject, 'createcase')) && p() && e('0');

// 测试步骤4：测试普通操作edit在有权限时是否可点击
r($caselibTest->isClickableTest($libObject, 'edit')) && p() && e('1');

// 测试步骤5：测试空字符串操作在有权限时是否可点击
r($caselibTest->isClickableTest($libObject, '')) && p() && e('1');

// 测试步骤6：测试大小写混合操作CREATECASE时是否可点击
$libObject2 = new stdclass();
$libObject2->lib = 2;
r($caselibTest->isClickableTest($libObject2, 'CREATECASE')) && p() && e('1');

// 测试步骤7：测试不存在的操作时权限检查
r($caselibTest->isClickableTest($libObject, 'nonexistent')) && p() && e('1');