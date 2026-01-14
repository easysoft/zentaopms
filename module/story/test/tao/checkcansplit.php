#!/usr/bin/env php
<?php

/**

title=测试 storyTao::checkCanSplit();
timeout=0
cid=18609

- 步骤1：正常需求无子需求 @1
- 步骤2：需求已有相同类型子需求 @0
- 步骤3：需求有不同类型子需求 @1
- 步骤4：需求有未删除和已删除的子需求 @0
- 步骤5：需求ID不存在 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->parent->range('0,0,0,0,2,3,4,4');
$table->type->range('story,story,story,story,story,requirement,story,story');
$table->deleted->range('0,0,0,0,0,0,0,1');
$table->gen(8);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->checkCanSplitTest((object)array('id' => 1, 'type' => 'story'))) && p() && e('1');  // 步骤1：正常需求无子需求
r($storyTest->checkCanSplitTest((object)array('id' => 2, 'type' => 'story'))) && p() && e('0');  // 步骤2：需求已有相同类型子需求
r($storyTest->checkCanSplitTest((object)array('id' => 3, 'type' => 'story'))) && p() && e('1');  // 步骤3：需求有不同类型子需求
r($storyTest->checkCanSplitTest((object)array('id' => 4, 'type' => 'story'))) && p() && e('0');  // 步骤4：需求有未删除和已删除的子需求
r($storyTest->checkCanSplitTest((object)array('id' => 999, 'type' => 'story'))) && p() && e('1'); // 步骤5：需求ID不存在