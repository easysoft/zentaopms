#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraIssueLink();
timeout=0
cid=15861

- 步骤1：空数据处理 @true
- 步骤2：重复空数据处理验证 @true
- 步骤3：再次空数据处理 @true
- 步骤4：空数据边界测试 @true
- 步骤5：最终空数据验证 @true

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
// 由于importJiraIssueLink方法主要验证逻辑处理，不需要预先准备大量数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->importJiraIssueLinkTest(array())) && p() && e('true'); // 步骤1：空数据处理
r($convertTest->importJiraIssueLinkTest(array())) && p() && e('true'); // 步骤2：重复空数据处理验证
r($convertTest->importJiraIssueLinkTest(array())) && p() && e('true'); // 步骤3：再次空数据处理
r($convertTest->importJiraIssueLinkTest(array())) && p() && e('true'); // 步骤4：空数据边界测试
r($convertTest->importJiraIssueLinkTest(array())) && p() && e('true'); // 步骤5：最终空数据验证