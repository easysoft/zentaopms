#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::__construct();
timeout=0
cid=15950

- 步骤1：验证构造函数正常执行属性result @normal
- 步骤2：验证父类model正确初始化属性result @1
- 步骤3：验证bi模块正确加载属性result @1
- 步骤4：验证dao数据库连接正常属性result @1
- 步骤5：验证模型实例类型正确属性result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$dataview = new dataviewModelTest();

r($dataview->__constructTest('normal')) && p('result') && e('normal'); // 步骤1：验证构造函数正常执行
r($dataview->__constructTest('parentConstructor')) && p('result') && e('1'); // 步骤2：验证父类model正确初始化
r($dataview->__constructTest('biModel')) && p('result') && e('1'); // 步骤3：验证bi模块正确加载
r($dataview->__constructTest('dao')) && p('result') && e('1'); // 步骤4：验证dao数据库连接正常
r($dataview->__constructTest('modelInstance')) && p('result') && e('1'); // 步骤5：验证模型实例类型正确