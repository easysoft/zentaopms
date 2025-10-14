#!/usr/bin/env php
<?php

/**

title=测试 docZen::assignStoryGradeData();
timeout=0
cid=0

- 步骤1：测试productStory类型属性storyType @story
- 步骤2：测试ER类型属性storyType @epic
- 步骤3：测试UR类型属性storyType @requirement
- 步骤4：测试planStory类型属性storyType @~~
- 步骤5：测试projectStory类型属性storyType @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$storygrade = zenData('storygrade');
$storygrade->type->range('story,epic,requirement');
$storygrade->grade->range('1-5');
$storygrade->name->range('初级,中级,高级,专家,大师');
$storygrade->status->range('enable{15}');
$storygrade->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->assignStoryGradeDataTest('productStory')) && p('storyType') && e('story'); // 步骤1：测试productStory类型
r($docTest->assignStoryGradeDataTest('ER')) && p('storyType') && e('epic'); // 步骤2：测试ER类型
r($docTest->assignStoryGradeDataTest('UR')) && p('storyType') && e('requirement'); // 步骤3：测试UR类型
r($docTest->assignStoryGradeDataTest('planStory')) && p('storyType') && e('~~'); // 步骤4：测试planStory类型
r($docTest->assignStoryGradeDataTest('projectStory')) && p('storyType') && e('~~'); // 步骤5：测试projectStory类型