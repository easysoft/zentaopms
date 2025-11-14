#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getLastReviewer();
timeout=0
cid=18540

- 执行storyTest模块的getLastReviewerTest方法  @0
- 执行storyTest模块的getLastReviewerTest方法，参数是1  @latest_reviewer
- 执行storyTest模块的getLastReviewerTest方法，参数是2  @dev2
- 执行storyTest模块的getLastReviewerTest方法，参数是3  @tester1
- 执行storyTest模块的getLastReviewerTest方法，参数是100  @skip_empty
- 执行storyTest模块的getLastReviewerTest方法，参数是-1  @0
- 执行storyTest模块的getLastReviewerTest方法，参数是5  @latest_reviewer

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 准备测试数据
$action = zenData('action');
$action->id->range('1-12');
$action->objectType->range('story{12}');
$action->objectID->range('1{2},2{2},3{1},4{2},100{2},5{3}');
$action->execution->range('0{12}');
$action->gen(12);

$history = zenData('history');
$history->id->range('1-12');
$history->action->range('1,2,3,4,5,6,7,8,9,10,11,12');
$history->field->range('reviewer{6},reviewers{6}');
$history->old->range('``{12}');
$history->new->range('old_reviewer,latest_reviewer,dev1,dev2,tester1,,,final_reviewer,empty_test,last_one,skip_empty,valid_result');
$history->gen(12);

// 用户登录
su('admin');

// 创建测试实例
$storyTest = new storyTest();

// 测试步骤1：不存在的需求ID查询
r($storyTest->getLastReviewerTest(0)) && p() && e('0');

// 测试步骤2：存在审核记录的需求查询，返回最新的reviewer（action id=2最新）
r($storyTest->getLastReviewerTest(1)) && p() && e('latest_reviewer');

// 测试步骤3：多条审核记录的需求查询，按ID倒序获取最新的（action id=4最新）
r($storyTest->getLastReviewerTest(2)) && p() && e('dev2');

// 测试步骤4：测试reviewer字段记录（action id=5）
r($storyTest->getLastReviewerTest(3)) && p() && e('tester1');

// 测试步骤5：测试多个审核记录需求查询
r($storyTest->getLastReviewerTest(100)) && p() && e('skip_empty');

// 测试步骤6：负数ID边界值测试
r($storyTest->getLastReviewerTest(-1)) && p() && e('0');

// 测试步骤7：包含多个审核记录的需求查询
r($storyTest->getLastReviewerTest(5)) && p() && e('latest_reviewer');