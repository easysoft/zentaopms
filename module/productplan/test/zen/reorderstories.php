#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::reorderStories();
timeout=0
cid=17667

- 步骤1:普通需求列表 @1
- 步骤2:父子关系需求 @1
- 步骤3:子需求在前也能正确排序 @1
- 步骤4:只有子需求 @1
- 步骤5:混合需求类型 @1
- 步骤6:所有类型session已设置 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
zenData('story')->loadYaml('reorderstories/story', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$productplanTest = new productplanZenTest();

// 5. 准备测试数据并执行测试步骤
global $tester;

// 测试步骤1: 测试普通需求列表(无父子关系)
$tester->dao->select('id, parent')->from(TABLE_STORY)->where('id')->in('1,2,3')->orderBy('id');
$result1 = $productplanTest->reorderStoriesTest();
r(is_object($result1) && isset($result1->storyBrowseList) && !empty($result1->storyBrowseList)) && p() && e('1'); // 步骤1:普通需求列表

// 测试步骤2: 测试带有父子关系的需求列表(父需求ID=4,子需求ID=5,6)
$tester->dao->select('id, parent')->from(TABLE_STORY)->where('id')->in('4,5,6')->orderBy('id');
$result2 = $productplanTest->reorderStoriesTest();
r(is_object($result2) && isset($result2->storyBrowseList) && !empty($result2->storyBrowseList)) && p() && e('1'); // 步骤2:父子关系需求

// 测试步骤3: 测试子需求在父需求之前的情况
$tester->dao->select('id, parent')->from(TABLE_STORY)->where('id')->in('5,6,4')->orderBy('id');
$result3 = $productplanTest->reorderStoriesTest();
r(is_object($result3) && isset($result3->storyBrowseList) && !empty($result3->storyBrowseList)) && p() && e('1'); // 步骤3:子需求在前也能正确排序

// 测试步骤4: 测试只有子需求没有父需求
$tester->dao->select('id, parent')->from(TABLE_STORY)->where('id')->in('5,6')->orderBy('id');
$result4 = $productplanTest->reorderStoriesTest();
r(is_object($result4) && isset($result4->storyBrowseList) && !empty($result4->storyBrowseList)) && p() && e('1'); // 步骤4:只有子需求

// 测试步骤5: 测试混合独立需求和父子需求
$tester->dao->select('id, parent')->from(TABLE_STORY)->where('id')->in('1,4,5,7')->orderBy('id');
$result5 = $productplanTest->reorderStoriesTest();
r(is_object($result5) && isset($result5->storyBrowseList) && !empty($result5->storyBrowseList)) && p() && e('1'); // 步骤5:混合需求类型

// 测试步骤6: 验证所有类型的 session 都被设置
$tester->dao->select('id, parent')->from(TABLE_STORY)->where('id')->in('1,2')->orderBy('id');
$result6 = $productplanTest->reorderStoriesTest();
r(isset($result6->storyBrowseList) && isset($result6->epicBrowseList) && isset($result6->requirementBrowseList)) && p() && e('1'); // 步骤6:所有类型session已设置