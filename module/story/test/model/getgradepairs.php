#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getGradePairs();
timeout=0
cid=18537

- 步骤1：默认参数获取story类型启用等级
 - 属性1 @SR
 - 属性2 @子
- 步骤2：获取requirement类型启用等级属性1 @UR
- 步骤3：获取epic类型所有等级属性1 @BR
- 步骤4：带附加列表参数查询
 - 属性1 @SR
 - 属性2 @子
- 步骤5：不存在的类型参数 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('storygrade');
$table->type->range('story,story,requirement,epic');
$table->grade->range('1,2,1,1');
$table->name->range('SR,子,UR,BR');
$table->status->range('enable,enable,enable,disable');
$table->gen(4);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->getGradePairsTest()) && p('1,2') && e('SR,子'); // 步骤1：默认参数获取story类型启用等级
r($storyTest->getGradePairsTest('requirement')) && p('1') && e('UR'); // 步骤2：获取requirement类型启用等级
r($storyTest->getGradePairsTest('epic', 'all')) && p('1') && e('BR'); // 步骤3：获取epic类型所有等级
r($storyTest->getGradePairsTest('story', 'enable', array(2))) && p('1,2') && e('SR,子'); // 步骤4：带附加列表参数查询
r($storyTest->getGradePairsTest('nonexistent')) && p() && e(0); // 步骤5：不存在的类型参数