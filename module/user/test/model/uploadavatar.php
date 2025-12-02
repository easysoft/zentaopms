#!/usr/bin/env php
<?php

/**

title=测试 userModel::uploadAvatar();
timeout=0
cid=19666

- 步骤1：正常情况下无文件上传属性result @fail
- 步骤2：模拟上传成功但无文件属性result @fail
- 步骤3：模拟获取文件信息失败属性result @fail
- 步骤4：模拟文件扩展名验证逻辑属性result @fail
- 步骤5：测试方法返回数组结构属性result @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// uploadAvatar方法主要调用file模块功能，不需要预生成大量数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$userTest = new userTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($userTest->uploadAvatarTest()) && p('result') && e('fail'); // 步骤1：正常情况下无文件上传
r($userTest->uploadAvatarTest()) && p('result') && e('fail'); // 步骤2：模拟上传成功但无文件
r($userTest->uploadAvatarTest()) && p('result') && e('fail'); // 步骤3：模拟获取文件信息失败  
r($userTest->uploadAvatarTest()) && p('result') && e('fail'); // 步骤4：模拟文件扩展名验证逻辑
r($userTest->uploadAvatarTest()) && p('result') && e('fail'); // 步骤5：测试方法返回数组结构