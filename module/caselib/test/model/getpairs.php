#!/usr/bin/env php
<?php

/**

title=测试 caselibModel::getPairs();
timeout=0
cid=0

- 步骤1：获取全部用例库键值对，按ID降序
 - 属性8 @Test Lib
 - 属性7 @测试库2
 - 属性6 @测试库1
 - 属性5 @Library02
- 步骤2：获取review类型用例库键值对（数据库无reviewers字段，返回空） @0
- 步骤3：按名称升序获取用例库键值对
 - 属性7 @Case Library
 - 属性3 @Library01
 - 属性2 @Library02
 - 属性8 @Test Lib
- 步骤4：使用无效类型参数，返回所有符合条件的
 - 属性8 @Test Lib
 - 属性7 @测试库2
 - 属性6 @测试库1
 - 属性5 @Library02
- 步骤5：分页获取用例库键值对
 - 属性8 @Test Lib
 - 属性7 @测试库2
 - 属性6 @测试库1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

// 准备测试数据：创建用例库记录（type=library, product=0, deleted=0）
$testsuite = zenData('testsuite');
$testsuite->id->range('1-8');
$testsuite->name->range('用例库A,用例库B,Library01,Library02,测试库1,测试库2,Case Library,Test Lib');
$testsuite->product->range('0{8}');  // 所有都是用例库(product=0)
$testsuite->type->range('library{8}');  // 所有都是library类型
$testsuite->deleted->range('0{8}');  // 所有未删除
$testsuite->order->range('1-8');
$testsuite->addedBy->range('admin{8}');
$testsuite->gen(8);

zenData('user')->gen(1);

su('admin');

$caselibTest = new caselibTest();

r($caselibTest->getPairsTest('all', 'id_desc')) && p('8,7,6,5') && e('Test Lib,测试库2,测试库1,Library02'); // 步骤1：获取全部用例库键值对，按ID降序
r($caselibTest->getPairsTest('review', 'id_desc')) && p() && e('0'); // 步骤2：获取review类型用例库键值对（数据库无reviewers字段，返回空）
r($caselibTest->getPairsTest('all', 'name_asc')) && p('7,3,2,8') && e('Case Library,Library01,Library02,Test Lib'); // 步骤3：按名称升序获取用例库键值对
r($caselibTest->getPairsTest('invalid', 'id_desc')) && p('8,7,6,5') && e('Test Lib,测试库2,测试库1,Library02'); // 步骤4：使用无效类型参数，返回所有符合条件的
r($caselibTest->getPairsTest('all', 'id_desc', (object)array('recPerPage' => 3, 'pageID' => 1))) && p('8,7,6') && e('Test Lib,测试库2,测试库1'); // 步骤5：分页获取用例库键值对