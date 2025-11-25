#!/usr/bin/env php
<?php

/**

title=测试 docZen::responseAfterMove();
timeout=0
cid=16219

- 步骤1:移动文档到mine空间
 - 属性result @success
 - 属性closeModal @1
- 步骤2:移动文档到custom空间
 - 属性result @success
 - 属性closeModal @1
- 步骤3:移动库到product空间,空间类型改变
 - 属性result @success
 - 属性closeModal @1
- 步骤4:移动库到project空间,空间类型改变
 - 属性result @success
 - 属性closeModal @1
- 步骤5:移动库到mine空间,空间类型未改变
 - 属性result @success
 - 属性closeModal @1
- 步骤6:移动文档到product空间
 - 属性result @success
 - 属性closeModal @1
- 步骤7:移动库到custom空间,空间类型改变
 - 属性result @success
 - 属性closeModal @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

r($docTest->responseAfterMoveTest('mine.1', 1, 1, false)) && p('result;closeModal') && e('success;1'); // 步骤1:移动文档到mine空间
r($docTest->responseAfterMoveTest('custom.2', 2, 2, false)) && p('result;closeModal') && e('success;1'); // 步骤2:移动文档到custom空间
r($docTest->responseAfterMoveTest('product.3', 3, 0, true)) && p('result;closeModal') && e('success;1'); // 步骤3:移动库到product空间,空间类型改变
r($docTest->responseAfterMoveTest('project.4', 4, 0, true)) && p('result;closeModal') && e('success;1'); // 步骤4:移动库到project空间,空间类型改变
r($docTest->responseAfterMoveTest('mine.5', 5, 0, false)) && p('result;closeModal') && e('success;1'); // 步骤5:移动库到mine空间,空间类型未改变
r($docTest->responseAfterMoveTest('product.6', 6, 3, false)) && p('result;closeModal') && e('success;1'); // 步骤6:移动文档到product空间
r($docTest->responseAfterMoveTest('custom.7', 7, 0, true)) && p('result;closeModal') && e('success;1'); // 步骤7:移动库到custom空间,空间类型改变