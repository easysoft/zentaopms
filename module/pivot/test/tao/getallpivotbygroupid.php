#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getAllPivotByGroupID();
timeout=0
cid=0

- 步骤1：正常分组查询（应返回2个已发布的透视表） @2
- 步骤2：不存在分组查询 @0
- 步骤3：分组ID为0查询 @0
- 步骤4：负数分组ID查询 @0
- 步骤5：验证透视表属性（按id降序，应该是1002在前）
 - 第0条的id属性 @1002
 - 第0条的0:name属性 @透视表2详细信息

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 生成0条数据来避免复杂的数据依赖问题
zenData('pivot')->gen(0);
zenData('pivotspec')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($pivotTest->getAllPivotByGroupIDTest(60)) && p() && e('2');                            // 步骤1：正常分组查询（应返回2个已发布的透视表）
r($pivotTest->getAllPivotByGroupIDTest(999)) && p() && e('0');                          // 步骤2：不存在分组查询
r($pivotTest->getAllPivotByGroupIDTest(0)) && p() && e('0');                            // 步骤3：分组ID为0查询
r($pivotTest->getAllPivotByGroupIDTest(-1)) && p() && e('0');                           // 步骤4：负数分组ID查询
r($pivotTest->getAllPivotByGroupIDTest(60)) && p('0:id,0:name') && e('1002,透视表2详细信息'); // 步骤5：验证透视表属性（按id降序，应该是1002在前）