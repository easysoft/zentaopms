#!/usr/bin/env php
<?php

/**

title=测试 requirementModel::getToAndCcList();
timeout=0
cid=18192

- 测试步骤1：正常story对象和changed动作类型 @admin,user1
- 测试步骤2：story对象无assignedTo但有mailto @user1
- 测试步骤3：story对象无assignedTo也无mailto @0
- 测试步骤4：story状态为closed时创建人加入抄送 @user2,creator
- 测试步骤5：测试reviewed动作类型不追加评审人 @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

zenData('storyreview')->gen(0);
$zd_review = zenData('storyreview');
$zd_review->story->range('1,4,5');
$zd_review->version->range('1');
$zd_review->reviewer->range('user1');
$zd_review->result->range('');
$zd_review->gen(3);

zenData('projectstory')->gen(0);
zenData('team')->gen(0);
zenData('task')->gen(0);

$requirementTest = new requirementModelTest();

// 测试步骤1：正常story对象和changed动作类型
$story1 = new stdClass();
$story1->id         = 1;
$story1->assignedTo = 'admin';
$story1->mailto     = 'user2';
$story1->type       = 'story';
$story1->version    = 1;
$story1->status     = 'active';
$result1 = $requirementTest->getToAndCcListTest($story1, 'changed');
r($result1[0]) && p() && e('admin,user1'); // 测试步骤1：正常story对象和changed动作类型

// 测试步骤2：story对象无assignedTo但有mailto
$story2 = new stdClass();
$story2->id         = 2;
$story2->assignedTo = '';
$story2->mailto     = 'user1,user2,user3';
$story2->type       = 'story';
$story2->version    = 1;
$story2->status     = 'active';
$result2 = $requirementTest->getToAndCcListTest($story2, 'opened');
r($result2[0]) && p() && e('user1'); // 测试步骤2：story对象无assignedTo但有mailto

// 测试步骤3：story对象无assignedTo也无mailto
$story3 = new stdClass();
$story3->id         = 3;
$story3->assignedTo = '';
$story3->mailto     = '';
$story3->type       = 'story';
$story3->version    = 1;
$story3->status     = 'active';
$result3 = $requirementTest->getToAndCcListTest($story3, 'opened');
r($result3) && p() && e('0'); // 测试步骤3：story对象无assignedTo也无mailto

// 测试步骤4：story状态为closed
$story4 = new stdClass();
$story4->id         = 4;
$story4->assignedTo = 'admin';
$story4->mailto     = 'user2';
$story4->type       = 'story';
$story4->version    = 1;
$story4->status     = 'closed';
$story4->openedBy   = 'creator';
$result4 = $requirementTest->getToAndCcListTest($story4, 'opened');
r($result4[1]) && p() && e('user2,creator'); // 测试步骤4：story状态为closed时创建人加入抄送

// 测试步骤5：测试reviewed动作类型对story类型
$story5 = new stdClass();
$story5->id         = 5;
$story5->assignedTo = 'admin';
$story5->mailto     = 'user2';
$story5->type       = 'story';
$story5->version    = 1;
$story5->status     = 'active';
$result5 = $requirementTest->getToAndCcListTest($story5, 'reviewed');
r($result5[0]) && p() && e('admin'); // 测试步骤5：测试reviewed动作类型对story
