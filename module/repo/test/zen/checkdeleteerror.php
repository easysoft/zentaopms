#!/usr/bin/env php
<?php

/**

title=测试 repoZen::checkDeleteError();
timeout=0
cid=0

- 步骤1：正常情况无关联 @0
- 步骤2：有设计关联 @1
- 步骤3：不存在的仓库ID @0
- 步骤4：有作业关联 @1
- 步骤5：无效仓库ID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('测试仓库{1-10}');
$table->SCM->range('Git,Gitlab');
$table->deleted->range('0');
$table->gen(5);

$relationTable = zenData('relation');
$relationTable->id->range('1-5');
$relationTable->AType->range('design');
$relationTable->AID->range('1001-1005');
$relationTable->extra->range('2,3,4,5,6');
$relationTable->gen(3);

$jobTable = zenData('job');
$jobTable->id->range('1-5');
$jobTable->name->range('测试作业{1-5}');
$jobTable->repo->range('3,4,5,6,7');
$jobTable->deleted->range('0');
$jobTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($repoTest->checkDeleteErrorTest(1)) && p() && e('0'); // 步骤1：正常情况无关联
r(strlen($repoTest->checkDeleteErrorTest(2)) > 0) && p() && e('1'); // 步骤2：有设计关联
r($repoTest->checkDeleteErrorTest(999)) && p() && e('0'); // 步骤3：不存在的仓库ID
r(strlen($repoTest->checkDeleteErrorTest(3)) > 0) && p() && e('1'); // 步骤4：有作业关联
r($repoTest->checkDeleteErrorTest(0)) && p() && e('0'); // 步骤5：无效仓库ID