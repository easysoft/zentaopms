#!/usr/bin/env php
<?php

/**

title=测试 todoZen::exportTodoInfo();
timeout=0
cid=19299

- 步骤1：正常数据导出返回数组长度验证 @2
- 步骤2：空待办数组导出验证 @2
- 步骤3：字段过滤功能验证 @2
- 步骤4：字段翻译功能验证 @2
- 步骤5：待办数据完整性验证 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 准备测试数据
$todos = array(
    (object)array(
        'id' => 1,
        'name' => '测试待办1',
        'type' => 'custom'
    ),
    (object)array(
        'id' => 2,
        'name' => '测试待办2',
        'type' => 'bug'
    )
);

$todoLang = (object)array(
    'id' => 'ID',
    'name' => '名称',
    'type' => '类型',
    'account' => '用户'
);

// 4. 🔴 强制要求：必须包含至少5个测试步骤
$result1 = $todoTest->exportTodoInfoTest($todos, 'id,name', $todoLang);
r(count($result1)) && p() && e(2); // 步骤1：正常数据导出返回数组长度验证

$result2 = $todoTest->exportTodoInfoTest(array(), 'id,name', $todoLang);
r(count($result2)) && p() && e(2); // 步骤2：空待办数组导出验证

$result3 = $todoTest->exportTodoInfoTest($todos, 'id,objectID,private', $todoLang);
r(count($result3)) && p() && e(2); // 步骤3：字段过滤功能验证

$result4 = $todoTest->exportTodoInfoTest($todos, 'account', $todoLang);
r(count($result4)) && p() && e(2); // 步骤4：字段翻译功能验证

$result5 = $todoTest->exportTodoInfoTest($todos, 'id,name,type', $todoLang);
r(count($result5)) && p() && e(2); // 步骤5：待办数据完整性验证