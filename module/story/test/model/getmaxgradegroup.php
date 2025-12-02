#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getMaxGradeGroup();
timeout=0
cid=18543

- 步骤1：默认状态获取最大等级分组
 - 属性story @5
 - 属性requirement @2
 - 属性epic @1
- 步骤2：所有状态获取最大等级分组
 - 属性story @5
 - 属性requirement @2
 - 属性epic @2
- 步骤3：启用状态获取最大等级分组
 - 属性story @5
 - 属性requirement @2
 - 属性epic @1
- 步骤4：无效状态获取最大等级分组 @0
- 步骤5：禁用状态获取最大等级分组属性epic @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$storygrade = zenData('storygrade');
$storygrade->type->range('story,story,story,requirement,requirement,epic,epic');
$storygrade->grade->range('1,3,5,1,2,1,2');
$storygrade->name->range('SR,SR3,SR5,UR,UR2,BR,BR2');
$storygrade->status->range('enable,enable,enable,enable,enable,enable,disable');
$storygrade->gen(7);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->getMaxGradeGroupTest()) && p('story,requirement,epic') && e('5,2,1'); // 步骤1：默认状态获取最大等级分组
r($storyTest->getMaxGradeGroupTest('all')) && p('story,requirement,epic') && e('5,2,2'); // 步骤2：所有状态获取最大等级分组
r($storyTest->getMaxGradeGroupTest('enable')) && p('story,requirement,epic') && e('5,2,1'); // 步骤3：启用状态获取最大等级分组
r($storyTest->getMaxGradeGroupTest('invalid')) && p() && e('0'); // 步骤4：无效状态获取最大等级分组
r($storyTest->getMaxGradeGroupTest('disable')) && p('epic') && e('2'); // 步骤5：禁用状态获取最大等级分组