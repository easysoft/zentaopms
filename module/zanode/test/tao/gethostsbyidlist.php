#!/usr/bin/env php
<?php

/**

title=测试 zanodeTao::getHostsByIDList();
timeout=0
cid=19848

- 步骤1：正常多个主机ID查询属性count @3
- 步骤2：单个存在主机ID查询
 - 属性count @1
 - 属性firstId @1
 - 属性firstStatus @online
- 步骤3：不存在主机ID查询属性count @0
- 步骤4：空数组参数查询属性count @0
- 步骤5：混合存在与不存在ID查询属性count @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('host');
$table->id->range('1-10');
$table->status->range('online{7},offline{3}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$zanodeTest = new zanodeTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($zanodeTest->getHostsByIDListTest(array(1, 2, 3))) && p('count') && e('3'); // 步骤1：正常多个主机ID查询
r($zanodeTest->getHostsByIDListTest(array(1))) && p('count,firstId,firstStatus') && e('1,1,online'); // 步骤2：单个存在主机ID查询
r($zanodeTest->getHostsByIDListTest(array(999))) && p('count') && e('0'); // 步骤3：不存在主机ID查询
r($zanodeTest->getHostsByIDListTest(array())) && p('count') && e('0'); // 步骤4：空数组参数查询
r($zanodeTest->getHostsByIDListTest(array(1, 999, 2))) && p('count') && e('2'); // 步骤5：混合存在与不存在ID查询