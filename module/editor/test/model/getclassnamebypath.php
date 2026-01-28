#!/usr/bin/env php
<?php

/**

title=测试 editorModel::getClassNameByPath();
timeout=0
cid=16233

- 测试步骤1：module路径 @1
- 测试步骤2：ext扩展路径 @1
- 测试步骤3：extension路径 @1
- 测试步骤4：不包含特殊标识路径 @1
- 测试步骤5：空路径测试 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$editor = new editorModelTest();

r($editor->getClassNameByPathTest(1)) && p() && e('1');    // 测试步骤1：module路径
r($editor->getClassNameByPathTest(2)) && p() && e('1');    // 测试步骤2：ext扩展路径
r($editor->getClassNameByPathTest(3)) && p() && e('1');    // 测试步骤3：extension路径
r($editor->getClassNameByPathTest(4)) && p() && e('1');    // 测试步骤4：不包含特殊标识路径
r($editor->getClassNameByPathTest(5)) && p() && e('1');    // 测试步骤5：空路径测试