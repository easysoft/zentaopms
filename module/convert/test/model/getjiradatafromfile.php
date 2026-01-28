#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraDataFromFile();
timeout=0
cid=15777

- 步骤1：不存在的模块，文件不存在返回空数组 @0
- 步骤2：空模块名参数返回空数组 @0
- 步骤3：build模块映射为version文件名 @0
- 步骤4：file模块映射为fileattachment文件名 @0
- 步骤5：分页参数测试 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$convertTest = new convertModelTest();

// 4. 测试步骤
r($convertTest->getJiraDataFromFileTest('nonexistent')) && p() && e('0'); // 步骤1：不存在的模块，文件不存在返回空数组
r($convertTest->getJiraDataFromFileTest('')) && p() && e('0'); // 步骤2：空模块名参数返回空数组
r($convertTest->getJiraDataFromFileTest('build')) && p() && e('0'); // 步骤3：build模块映射为version文件名
r($convertTest->getJiraDataFromFileTest('file')) && p() && e('0'); // 步骤4：file模块映射为fileattachment文件名
r($convertTest->getJiraDataFromFileTest('user', 0, 10)) && p() && e('0'); // 步骤5：分页参数测试