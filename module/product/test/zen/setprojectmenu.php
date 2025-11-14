#!/usr/bin/env php
<?php

/**

title=测试 productZen::setProjectMenu();
timeout=0
cid=17613

- 步骤1:正常场景,传入有效的productID和branch
 - 属性executionSuccess @1
 - 属性branchMatch @1
- 步骤2:branch为空但preBranch有值
 - 属性branchMatch @1
 - 属性executionSuccess @1
- 步骤3:branch和preBranch都为空
 - 属性branchMatch @1
 - 属性executionSuccess @1
- 步骤4:branch有值,preBranch也有值
 - 属性branchMatch @1
 - 属性executionSuccess @1
- 步骤5:传入不同的branch值
 - 属性branchMatch @1
 - 属性executionSuccess @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->setProjectMenuTest(1, 'main', '')) && p('executionSuccess,branchMatch') && e('1,1'); // 步骤1:正常场景,传入有效的productID和branch
r($productTest->setProjectMenuTest(1, '', 'dev')) && p('branchMatch,executionSuccess') && e('1,1'); // 步骤2:branch为空但preBranch有值
r($productTest->setProjectMenuTest(2, '', '')) && p('branchMatch,executionSuccess') && e('1,1'); // 步骤3:branch和preBranch都为空
r($productTest->setProjectMenuTest(3, 'test', 'dev')) && p('branchMatch,executionSuccess') && e('1,1'); // 步骤4:branch有值,preBranch也有值
r($productTest->setProjectMenuTest(1, 'feature', '')) && p('branchMatch,executionSuccess') && e('1,1'); // 步骤5:传入不同的branch值