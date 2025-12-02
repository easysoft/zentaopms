#!/usr/bin/env php
<?php

/**

title=测试 biModel::unzipFile();
timeout=0
cid=15217

- 步骤1：使用不存在的ZIP文件路径 @1
- 步骤2：使用空的文件参数 @1  
- 步骤3：使用空的解压目标路径 @1
- 步骤4：使用空的extractFile参数 @1
- 步骤5：使用无效的ZIP文件路径格式 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($biTest->unzipFileTest('/tmp/', '/nonexistent/path.zip', 'test.txt')) && p() && e('1'); // 步骤1：使用不存在的ZIP文件路径
r($biTest->unzipFileTest('/tmp/', '', 'test.txt')) && p() && e('1'); // 步骤2：使用空的文件参数
r($biTest->unzipFileTest('', '/path/to/file.zip', 'test.txt')) && p() && e('1'); // 步骤3：使用空的解压目标路径
r($biTest->unzipFileTest('/tmp/', '/path/to/file.zip', '')) && p() && e('1'); // 步骤4：使用空的extractFile参数
r($biTest->unzipFileTest('/tmp/', 'invalid_path_format', 'test.txt')) && p() && e('1'); // 步骤5：使用无效的ZIP文件路径格式