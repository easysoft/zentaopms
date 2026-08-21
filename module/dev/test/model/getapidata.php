#!/usr/bin/env php
<?php

/**

title=测试 devModel::getAPIData();
timeout=0
cid=16000

- 步骤1：默认参数apiID=0测试，返回3个元素的数组 @3
- 步骤2：指定存在的API ID测试第0条的id属性 @1
- 步骤3：不存在的API ID测试，返回3个元素的数组 @3
- 步骤4：验证typeList数组长度 @12
- 步骤5：验证treeMenu数组长度 @16

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
// 不需要准备数据，因为getAPIData方法使用固定的demo数据文件

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$devTest = new devModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($devTest->getAPIDataTest(0, 'v1'))) && p() && e(3); // 步骤1：默认参数apiID=0测试，返回3个元素的数组
r($devTest->getAPIDataTest(1, 'v1')) && p('0:id') && e(1); // 步骤2：指定存在的API ID测试
r(count($devTest->getAPIDataTest(999999, 'v1'))) && p() && e(3); // 步骤3：不存在的API ID测试，返回3个元素的数组
r(count($devTest->getAPIDataTest(0, 'v1')[1])) && p() && e(12); // 步骤4：验证typeList数组长度
r(count($devTest->getAPIDataTest(0, 'v1')[2])) && p() && e(16); // 步骤5：验证treeMenu数组长度