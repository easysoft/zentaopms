#!/usr/bin/env php
<?php

/**

title=测试 docModel::getZentaoList();
timeout=0
cid=16136

- 步骤1：正常查询存在记录
 - 属性id @1
 - 属性type @zentao
- 步骤2：查询不存在的blockID @0
- 步骤3：blockID为0 @0
- 步骤4：blockID为负数 @0
- 步骤5：验证另一个正常记录
 - 属性id @2
 - 属性type @zentao

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('docblock');
$table->id->range('1-5');
$table->doc->range('1-5');
$table->type->range('zentao{2},list{2},table{1}');
$table->content->range('{"test": "data"}{2},null{2},""{1}');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$docTest = new docModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($docTest->getZentaoListTest(1)) && p('id,type') && e('1,zentao'); // 步骤1：正常查询存在记录
r($docTest->getZentaoListTest(999)) && p() && e('0'); // 步骤2：查询不存在的blockID
r($docTest->getZentaoListTest(0)) && p() && e('0'); // 步骤3：blockID为0
r($docTest->getZentaoListTest(-1)) && p() && e('0'); // 步骤4：blockID为负数  
r($docTest->getZentaoListTest(2)) && p('id,type') && e('2,zentao'); // 步骤5：验证另一个正常记录