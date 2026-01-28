#!/usr/bin/env php
<?php

/**

title=测试 docModel::getDocsByParent();
timeout=0
cid=16082

- 步骤1：查询父文档ID为1的子文档，返回3个 @3
- 步骤2：查询父文档ID为2的子文档，返回2个 @2
- 步骤3：查询父文档ID为999的子文档，返回3个 @3
- 步骤4：查询父文档ID为0的顶级文档，返回5个 @5
- 步骤5：验证子文档状态为normal第4条的status属性 @normal

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('doc');
$table->parent->range('0,0,0,1,1,1,2,2,3,3,0,0,999,999,999');
$table->status->range('normal');
$table->deleted->range('0');
$table->gen(15);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$docTest = new docModelTest();

// 5. 测试步骤：必须包含至少5个测试步骤
r(count($docTest->getDocsByParentTest(1))) && p() && e('3'); // 步骤1：查询父文档ID为1的子文档，返回3个
r(count($docTest->getDocsByParentTest(2))) && p() && e('2'); // 步骤2：查询父文档ID为2的子文档，返回2个
r(count($docTest->getDocsByParentTest(999))) && p() && e('3'); // 步骤3：查询父文档ID为999的子文档，返回3个
r(count($docTest->getDocsByParentTest(0))) && p() && e('5'); // 步骤4：查询父文档ID为0的顶级文档，返回5个
r($docTest->getDocsByParentTest(1)) && p('4:status') && e('normal'); // 步骤5：验证子文档状态为normal