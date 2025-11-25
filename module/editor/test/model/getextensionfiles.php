#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/editor.unittest.class.php';
su('admin');

/**

title=测试 editorModel::getExtensionFiles();
timeout=0
cid=16235

- 步骤1：测试todo模块扩展文件 @1
- 步骤2：测试user模块扩展文件 @1
- 步骤3：测试空模块名 @1
- 步骤4：测试不存在模块 @1
- 步骤5：测试特殊字符模块名 @1

*/

$editor = new editorTest();
r($editor->getExtensionFilesBasicTest('todo')) && p() && e(1);                          // 步骤1：测试todo模块扩展文件
r($editor->getExtensionFilesBasicTest('user')) && p() && e(1);                          // 步骤2：测试user模块扩展文件
r($editor->getExtensionFilesEmptyModuleTest()) && p() && e(1);                          // 步骤3：测试空模块名
r($editor->getExtensionFilesNonExistentModuleTest()) && p() && e(1);                    // 步骤4：测试不存在模块
r($editor->getExtensionFilesSpecialCharsModuleTest()) && p() && e(1);                   // 步骤5：测试特殊字符模块名