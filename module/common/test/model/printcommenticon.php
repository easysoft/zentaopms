#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printCommentIcon();
timeout=0
cid=0

- 步骤1：正常情况，无权限时返回false @alse
- 步骤2：空链接，无权限时返回false @alse
- 步骤3：带对象，无权限时返回false @alse
- 步骤4：特殊字符链接，无权限时返回false @alse
- 步骤5：长路径链接，无权限时返回false @alse

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($commonTest->printCommentIconTest('/action/comment/test', null)) && p() && e(false); // 步骤1：正常情况，无权限时返回false
r($commonTest->printCommentIconTest('', null)) && p() && e(false); // 步骤2：空链接，无权限时返回false
r($commonTest->printCommentIconTest('/action/comment/test', (object)array('id' => 1))) && p() && e(false); // 步骤3：带对象，无权限时返回false  
r($commonTest->printCommentIconTest('/action/comment/test&param=value', null)) && p() && e(false); // 步骤4：特殊字符链接，无权限时返回false
r($commonTest->printCommentIconTest('/action/comment/long_path_test', null)) && p() && e(false); // 步骤5：长路径链接，无权限时返回false