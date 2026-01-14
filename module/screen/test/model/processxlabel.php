#!/usr/bin/env php
<?php

/**

title=测试 screenModel::processXLabel();
timeout=0
cid=18279

- 步骤1：日期类型标签处理
 - 属性2023-11-03 @2023-11-03
 - 属性2023-11-07 @2023-11-07
 - 属性2023-11-13 @2023-11-13
- 步骤2：用户类型标签处理属性admin @A:admin
- 步骤3：产品类型标签处理属性1 @正常产品1
- 步骤4：无匹配选项的标签处理属性nonexistent @nonexistent
- 步骤5：空标签数组处理 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
zenData('user')->gen(5);
zenData('product')->gen(3);

// 用户登录
su('admin');

// 创建测试实例
$screenTest = new screenModelTest();

r($screenTest->processXLabelTest(array('2023-11-03', '2023-11-07', '2023-11-13'), 'date', 'bug', 'date')) && p('2023-11-03,2023-11-07,2023-11-13') && e('2023-11-03,2023-11-07,2023-11-13'); // 步骤1：日期类型标签处理
r($screenTest->processXLabelTest(array('admin', 'user1'), 'user', '', '')) && p('admin') && e('A:admin'); // 步骤2：用户类型标签处理
r($screenTest->processXLabelTest(array('1', '2'), 'product', '', '')) && p('1') && e('正常产品1'); // 步骤3：产品类型标签处理
r($screenTest->processXLabelTest(array('nonexistent'), 'user', '', '')) && p('nonexistent') && e('nonexistent'); // 步骤4：无匹配选项的标签处理
r($screenTest->processXLabelTest(array(), 'date', 'bug', 'date')) && p() && e('0'); // 步骤5：空标签数组处理