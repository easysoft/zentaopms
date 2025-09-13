#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printDocCollectListBlock();
timeout=0
cid=0

- 步骤1：验证方法执行成功属性success @1
- 步骤2：验证过滤后的文档数量属性count @0
- 步骤3：验证存在无收藏数文档属性hasZeroCollects @1
- 步骤4：验证排序功能属性sortOrder @desc
- 步骤5：验证无错误发生属性error @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('doc')->loadYaml('doc_printdoccollectlistblock', false, 2)->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printDocCollectListBlockTest()) && p('success') && e('1'); // 步骤1：验证方法执行成功
r($blockTest->printDocCollectListBlockTest()) && p('count') && e('0'); // 步骤2：验证过滤后的文档数量
r($blockTest->printDocCollectListBlockTest()) && p('hasZeroCollects') && e('1'); // 步骤3：验证存在无收藏数文档
r($blockTest->printDocCollectListBlockTest()) && p('sortOrder') && e('desc'); // 步骤4：验证排序功能
r($blockTest->printDocCollectListBlockTest()) && p('error') && e('~~'); // 步骤5：验证无错误发生