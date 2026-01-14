#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createStory();
cid=15845

- 执行convertTest模块的createStoryTest方法，参数是1, 1, 1, 'story',   @1
- 执行convertTest模块的createStoryTest方法，参数是2, 2, 2, 'requirement',   @1
- 执行convertTest模块的createStoryTest方法，参数是3, 3, 3, 'epic',   @1
- 执行convertTest模块的createStoryTest方法，参数是1, 1, 1, 'story', null, array  @0
- 执行convertTest模块的createStoryTest方法，参数是1, 1, 1, 'story',   @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$convertTest = new convertTaoTest();

r($convertTest->createStoryTest(1, 1, 1, 'story', (object)array('id' => 1001, 'summary' => '正常Story需求', 'description' => '详细描述', 'priority' => 2, 'issuestatus' => 'Open', 'issuetype' => 'Story', 'creator' => 'admin', 'created' => '2023-01-01 10:00:00', 'assignee' => 'user1'), array('zentaoFieldStory' => array(), 'zentaoReasonStory' => array(), 'zentaoStageStory' => array(), 'zentaoStatusStory' => array()))) && p() && e('1');
r($convertTest->createStoryTest(2, 2, 2, 'requirement', (object)array('id' => 1002, 'summary' => '正常Requirement需求', 'description' => '需求描述', 'priority' => 3, 'issuestatus' => 'Open', 'issuetype' => 'Requirement', 'creator' => 'admin', 'created' => '2023-01-02 10:00:00'), array('zentaoFieldRequirement' => array(), 'zentaoReasonRequirement' => array(), 'zentaoStageRequirement' => array(), 'zentaoStatusRequirement' => array()))) && p() && e('1');
r($convertTest->createStoryTest(3, 3, 3, 'epic', (object)array('id' => 1003, 'summary' => '正常Epic需求', 'description' => '史诗描述', 'priority' => 1, 'issuestatus' => 'Closed', 'issuetype' => 'Epic', 'creator' => 'admin', 'created' => '2023-01-03 10:00:00', 'assignee' => 'user2', 'resolution' => 'Done'), array('zentaoFieldEpic' => array(), 'zentaoReasonEpic' => array('Done' => 'done'), 'zentaoStageEpic' => array(), 'zentaoStatusEpic' => array()))) && p() && e('1');
r($convertTest->createStoryTest(1, 1, 1, 'story', null, array())) && p() && e('0');
r($convertTest->createStoryTest(1, 1, 1, 'story', (object)array(), array())) && p() && e('0');
