#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::getPairs();
timeout=0
cid=16654

- 步骤1：正常获取GitLab pairs，验证返回类型 @array
- 步骤2：验证ID为1的GitLab服务器名称属性1 @GitLab服务器
- 步骤3：验证ID为2的GitLab测试名称属性2 @GitLab测试
- 步骤4：验证只返回type为gitlab且未删除的记录数量 @5
- 步骤5：验证返回数组的第一个键为1 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$gitlabTest = new gitlabModelTest();

// 4. 执行测试步骤（必须包含至少5个测试步骤）
r(is_array($gitlabTest->getPairsTest())) && p() && e('1'); // 步骤1：正常获取GitLab pairs，验证返回类型
r($gitlabTest->getPairsTest()) && p('1') && e('GitLab服务器'); // 步骤2：验证ID为1的GitLab服务器名称
r($gitlabTest->getPairsTest()) && p('2') && e('GitLab测试'); // 步骤3：验证ID为2的GitLab测试名称
r(count($gitlabTest->getPairsTest())) && p() && e('5'); // 步骤4：验证只返回type为gitlab且未删除的记录数量
r(array_keys($gitlabTest->getPairsTest())) && p('0') && e('1'); // 步骤5：验证返回数组的第一个键为1
