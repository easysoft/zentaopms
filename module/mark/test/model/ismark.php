#!/usr/bin/env php
<?php

/**

title=测试 markModel::isMark();
timeout=0
cid=17046

- 步骤1：查询存在的标记记录第0条的objectID属性 @1
- 步骤2：查询不存在的对象ID @0
- 步骤3：查询不存在的对象类型 @0
- 步骤4：查询不存在的版本 @0
- 步骤5：查询不同的标记类型 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mark.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('mark');
$table->objectType->range('story,task,bug');
$table->objectID->range('1-10');
$table->version->range('1.0,1.1,2.0');
$table->account->range('admin,user1,user2');
$table->mark->range('view,edit,delete');
$table->extra->range('');
$table->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$markTest = new markTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($markTest->isMarkTest('story', 1, '1.0', 'view')) && p('0:objectID') && e('1'); // 步骤1：查询存在的标记记录
r($markTest->isMarkTest('story', 999, '1.0', 'view')) && p() && e('0'); // 步骤2：查询不存在的对象ID
r($markTest->isMarkTest('nonexistent', 1, '1.0', 'view')) && p() && e('0'); // 步骤3：查询不存在的对象类型
r($markTest->isMarkTest('story', 1, '9.9', 'view')) && p() && e('0'); // 步骤4：查询不存在的版本
r($markTest->isMarkTest('story', 1, '1.0', 'edit')) && p() && e('0'); // 步骤5：查询不同的标记类型