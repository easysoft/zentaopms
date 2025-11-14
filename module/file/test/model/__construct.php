#!/usr/bin/env php
<?php

/**

title=测试 fileModel::__construct();
timeout=0
cid=16491

- 步骤1：验证now属性为整数属性nowIsInt @1
- 步骤2：验证savePath包含upload路径属性savePathContainsUpload @1
- 步骤3：验证webPath包含upload路径属性webPathContainsUpload @1
- 步骤4：验证now为当前时间属性nowIsRecent @1
- 步骤5：验证继承了父类属性属性hasParentProperties @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';

// 2. zendata数据准备（__construct方法不依赖数据库数据）
// 由于测试构造函数，无需准备数据库数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$fileTest = new fileTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($fileTest->__constructTest()) && p('nowIsInt') && e('1'); // 步骤1：验证now属性为整数
r($fileTest->__constructTest()) && p('savePathContainsUpload') && e('1'); // 步骤2：验证savePath包含upload路径
r($fileTest->__constructTest()) && p('webPathContainsUpload') && e('1'); // 步骤3：验证webPath包含upload路径
r($fileTest->__constructTest()) && p('nowIsRecent') && e('1'); // 步骤4：验证now为当前时间
r($fileTest->__constructTest()) && p('hasParentProperties') && e('1'); // 步骤5：验证继承了父类属性