#!/usr/bin/env php
<?php

/**

title=测试 fileModel::getPathOfImportedFile();
timeout=0
cid=16511

- 步骤1：正常获取导入路径 @tmp/import
- 步骤2：验证路径以import结尾 @1
- 步骤3：多次调用一致性 @1
- 步骤4：验证包含tmp前缀 @1
- 步骤5：验证路径结构完整性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$file = new fileModelTest();

r($file->getPathOfImportedFileTest()) && p() && e('tmp/import');                    // 步骤1：正常获取导入路径
r($file->getPathOfImportedFileEndsWithImportTest()) && p() && e('1');              // 步骤2：验证路径以import结尾
r($file->getPathOfImportedFileConsistencyTest()) && p() && e('1');                 // 步骤3：多次调用一致性
r($file->getPathOfImportedFileContainsTmpTest()) && p() && e('1');                 // 步骤4：验证包含tmp前缀
r($file->getPathOfImportedFileValidStructureTest()) && p() && e('1');              // 步骤5：验证路径结构完整性