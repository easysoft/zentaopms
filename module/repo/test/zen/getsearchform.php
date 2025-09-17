#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getSearchForm();
timeout=0
cid=0

- 步骤1：无参数调用返回空查询对象
 - 属性committer @
 - 属性commit @
- 步骤2：传入有效queryID
 - 属性committer @admin
 - 属性commit @test123
- 步骤3：获取SQL查询字符串 @t1.`committer` = "admin"
- 步骤4：传入无效queryID返回默认值
 - 属性committer @
 - 属性commit @
- 步骤5：获取默认SQL查询 @ 1 = 1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen_getsearchform.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 本测试不依赖数据库表，直接模拟session数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenGetSearchFormTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($repoTest->getSearchFormTestClean()) && p('committer,commit') && e(','); // 步骤1：无参数调用返回空查询对象
r($repoTest->getSearchFormTestWithData()) && p('committer,commit') && e('admin,test123'); // 步骤2：传入有效queryID
r($repoTest->getSearchFormTestSql()) && p() && e('t1.`committer` = "admin"'); // 步骤3：获取SQL查询字符串
r($repoTest->getSearchFormTestInvalid()) && p('committer,commit') && e(','); // 步骤4：传入无效queryID返回默认值
r($repoTest->getSearchFormTestDefaultSql()) && p() && e(' 1 = 1'); // 步骤5：获取默认SQL查询