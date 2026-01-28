#!/usr/bin/env php
<?php

/**

title=测试 editorModel::getMethodCode();
timeout=0
cid=16236

- 测试步骤1：获取todo控制器create方法代码 @1
- 测试步骤2：获取todo模型create方法代码 @1
- 测试步骤3：获取todo控制器browse方法代码 @1
- 测试步骤4：获取task模块create方法代码 @1
- 测试步骤5：获取user模块login方法代码 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$editor = new editorModelTest();

r($editor->getMethodCodeTest('todo', 'create')) && p() && e('1'); // 测试步骤1：获取todo控制器create方法代码
r($editor->getMethodCodeTest('todo', 'create', 'Model')) && p() && e('1'); // 测试步骤2：获取todo模型create方法代码
r($editor->getMethodCodeTest('todo', 'browse')) && p() && e('1'); // 测试步骤3：获取todo控制器browse方法代码
r($editor->getMethodCodeTest('task', 'create')) && p() && e('1'); // 测试步骤4：获取task模块create方法代码
r($editor->getMethodCodeTest('user', 'login')) && p() && e('1'); // 测试步骤5：获取user模块login方法代码