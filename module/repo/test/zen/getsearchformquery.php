#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getSearchFormQuery();
timeout=0
cid=0

- 步骤1：无session数据时的默认查询结果属性begin @2023-01-01
- 步骤2：测试日期范围查询（大于等于操作符）属性begin @2023-12-31
- 步骤3：测试日期范围查询（小于等于操作符）属性end @admin
- 步骤4：测试提交者搜索条件属性committer @abc123
- 步骤5：测试提交ID搜索条件属性commit @

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 本测试不依赖数据库表，直接模拟session数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($repoTest->getSearchFormQueryTest()) && p('begin') && e('2023-01-01'); // 步骤1：无session数据时的默认查询结果
r($repoTest->getSearchFormQueryTestDateBegin()) && p('begin') && e('2023-12-31'); // 步骤2：测试日期范围查询（大于等于操作符）
r($repoTest->getSearchFormQueryTestDateEnd()) && p('end') && e('admin'); // 步骤3：测试日期范围查询（小于等于操作符）
r($repoTest->getSearchFormQueryTestCommitter()) && p('committer') && e('abc123'); // 步骤4：测试提交者搜索条件
r($repoTest->getSearchFormQueryTestCommit()) && p('commit') && e(''); // 步骤5：测试提交ID搜索条件