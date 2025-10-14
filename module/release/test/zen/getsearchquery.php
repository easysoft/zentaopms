#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::getSearchQuery();
timeout=0
cid=0

- 步骤1：有效查询ID，返回存储的SQL查询 @(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'
- 步骤2：无效查询ID，返回默认条件 @ 1 = 1
- 步骤3：查询ID为0，返回默认条件 @ 1 = 1
- 步骤4：另一个有效查询ID @(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'
- 步骤5：第三个有效查询ID @(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('userquery');
$table->loadYaml('userquery_getsearchquery', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$releaseTest = new releaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($releaseTest->getSearchQueryTest(1, true)) && p() && e("(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'"); // 步骤1：有效查询ID，返回存储的SQL查询
r($releaseTest->getSearchQueryTest(999, true)) && p() && e(' 1 = 1'); // 步骤2：无效查询ID，返回默认条件
r($releaseTest->getSearchQueryTest(0, true)) && p() && e(' 1 = 1'); // 步骤3：查询ID为0，返回默认条件
r($releaseTest->getSearchQueryTest(2, true)) && p() && e("(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'"); // 步骤4：另一个有效查询ID
r($releaseTest->getSearchQueryTest(3, true)) && p() && e("(( 1   AND t1.`name`  LIKE '%aa%' ) AND ( 1  )) AND deleted = '0'"); // 步骤5：第三个有效查询ID