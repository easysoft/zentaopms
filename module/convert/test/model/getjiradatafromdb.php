#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraDataFromDB();
timeout=0
cid=15776

- 步骤1：测试获取user模块数据，无数据库连接返回空数组 @0
- 步骤2：测试获取nodeassociation模块数据，无数据库连接返回空数组 @0
- 步骤3：测试获取fixversion模块数据，返回空数组 @0
- 步骤4：测试获取affectsversion模块数据，返回空数组 @0
- 步骤5：测试获取不存在的模块数据，返回空数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->getJiraDataFromDBTest('user', 0, 10)) && p() && e('0'); // 步骤1：测试获取user模块数据，无数据库连接返回空数组
r($convertTest->getJiraDataFromDBTest('nodeassociation', 0, 10)) && p() && e('0'); // 步骤2：测试获取nodeassociation模块数据，无数据库连接返回空数组
r($convertTest->getJiraDataFromDBTest('fixversion', 0, 10)) && p() && e('0'); // 步骤3：测试获取fixversion模块数据，返回空数组
r($convertTest->getJiraDataFromDBTest('affectsversion', 0, 10)) && p() && e('0'); // 步骤4：测试获取affectsversion模块数据，返回空数组
r($convertTest->getJiraDataFromDBTest('nonexistent', 0, 10)) && p() && e('0'); // 步骤5：测试获取不存在的模块数据，返回空数组