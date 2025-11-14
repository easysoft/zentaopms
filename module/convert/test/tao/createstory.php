#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createStory();
cid=15845

- 测试步骤1：正常创建story类型需求 >> 期望返回true
- 测试步骤2：正常创建requirement类型需求 >> 期望返回true
- 测试步骤3：正常创建epic类型需求 >> 期望返回true
- 测试步骤4：无效数据输入(null) >> 期望返回false
- 测试步骤5：无效数据输入(空对象) >> 期望返回false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

$convertTest = new convertTest();

r($convertTest->createStoryTest(1, 1, 1, 'story', (object)array('id' => 1001, 'summary' => '正常Story需求', 'description' => '详细描述', 'priority' => 2, 'issuestatus' => 'Open', 'issuetype' => 'Story', 'creator' => 'admin', 'created' => '2023-01-01 10:00:00', 'assignee' => 'user1'), array('zentaoFieldStory' => array(), 'zentaoReasonStory' => array(), 'zentaoStageStory' => array(), 'zentaoStatusStory' => array()))) && p() && e('1');
r($convertTest->createStoryTest(2, 2, 2, 'requirement', (object)array('id' => 1002, 'summary' => '正常Requirement需求', 'description' => '需求描述', 'priority' => 3, 'issuestatus' => 'Open', 'issuetype' => 'Requirement', 'creator' => 'admin', 'created' => '2023-01-02 10:00:00'), array('zentaoFieldRequirement' => array(), 'zentaoReasonRequirement' => array(), 'zentaoStageRequirement' => array(), 'zentaoStatusRequirement' => array()))) && p() && e('1');
r($convertTest->createStoryTest(3, 3, 3, 'epic', (object)array('id' => 1003, 'summary' => '正常Epic需求', 'description' => '史诗描述', 'priority' => 1, 'issuestatus' => 'Closed', 'issuetype' => 'Epic', 'creator' => 'admin', 'created' => '2023-01-03 10:00:00', 'assignee' => 'user2', 'resolution' => 'Done'), array('zentaoFieldEpic' => array(), 'zentaoReasonEpic' => array('Done' => 'done'), 'zentaoStageEpic' => array(), 'zentaoStatusEpic' => array()))) && p() && e('1');
r($convertTest->createStoryTest(1, 1, 1, 'story', null, array())) && p() && e('0');
r($convertTest->createStoryTest(1, 1, 1, 'story', (object)array(), array())) && p() && e('0');