#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getColumn();
timeout=0
cid=19415

- 步骤1:验证返回值类型 @object
- 步骤2:验证id属性属性id @1
- 步骤3:验证type属性属性type @backlog
- 步骤4:验证name属性属性name @Backlog
- 步骤5:验证limit属性属性limit @-1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 创建测试实例(变量名与模块名一致)
$tutorialTest = new tutorialModelTest();

// 3. 执行测试步骤
r(gettype($tutorialTest->getColumnTest())) && p() && e('object'); // 步骤1:验证返回值类型
r($tutorialTest->getColumnTest()) && p('id') && e('1'); // 步骤2:验证id属性
r($tutorialTest->getColumnTest()) && p('type') && e('backlog'); // 步骤3:验证type属性
r($tutorialTest->getColumnTest()) && p('name') && e('Backlog'); // 步骤4:验证name属性
r($tutorialTest->getColumnTest()) && p('limit') && e('-1'); // 步骤5:验证limit属性