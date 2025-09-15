#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processStoryRecord();
timeout=0
cid=0

- 执行searchTest模块的processStoryRecordTest方法，参数是$record1, 'story', $objectList1 属性url @/home/z/rzto/module/search/test/tao/processstoryrecord.php?m=story&f=storyView&id=1
- 执行searchTest模块的processStoryRecordTest方法，参数是$record2, 'story', $objectList2 属性url @/home/z/rzto/module/search/test/tao/processstoryrecord.php?m=assetlib&f=storyView&id=2
- 执行searchTest模块的processStoryRecordTest方法，参数是$record3, 'requirement', $objectList3 属性extraType @requirement
- 执行searchTest模块的processStoryRecordTest方法，参数是$record4, 'epic', $objectList4 属性extraType @epic
- 执行searchTest模块的processStoryRecordTest方法，参数是$record5, 'story', $objectList5 属性url @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

zendata('story')->loadYaml('story_processrecord', false, 2)->gen(10);

su('admin');

$searchTest = new searchTest();

// 测试步骤1：正常需求记录处理（story类型，无lib）
$record1 = new stdClass();
$record1->objectType = 'story';
$record1->objectID = 1;

$story1 = new stdClass();
$story1->lib = 0;
$story1->type = 'story';

$objectList1 = array('story' => array(1 => $story1));

r($searchTest->processStoryRecordTest($record1, 'story', $objectList1)) && p('url') && e('/home/z/rzto/module/search/test/tao/processstoryrecord.php?m=story&f=storyView&id=1');

// 测试步骤2：需求记录处理（story类型，有lib）
$record2 = new stdClass();
$record2->objectType = 'story';
$record2->objectID = 2;

$story2 = new stdClass();
$story2->lib = 1;
$story2->type = 'story';

$objectList2 = array('story' => array(2 => $story2));

r($searchTest->processStoryRecordTest($record2, 'story', $objectList2)) && p('url') && e('/home/z/rzto/module/search/test/tao/processstoryrecord.php?m=assetlib&f=storyView&id=2');

// 测试步骤3：用户需求记录处理（requirement类型）
$record3 = new stdClass();
$record3->objectType = 'requirement';
$record3->objectID = 3;

$story3 = new stdClass();
$story3->lib = 0;
$story3->type = 'requirement';

$objectList3 = array('requirement' => array(3 => $story3));

r($searchTest->processStoryRecordTest($record3, 'requirement', $objectList3)) && p('extraType') && e('requirement');

// 测试步骤4：业务需求记录处理（epic类型）
$record4 = new stdClass();
$record4->objectType = 'epic';
$record4->objectID = 4;

$story4 = new stdClass();
$story4->lib = 0;
$story4->type = 'epic';

$objectList4 = array('epic' => array(4 => $story4));

r($searchTest->processStoryRecordTest($record4, 'epic', $objectList4)) && p('extraType') && e('epic');

// 测试步骤5：空故事对象处理
$record5 = new stdClass();
$record5->objectType = 'story';
$record5->objectID = 999;

$objectList5 = array('story' => array());

r($searchTest->processStoryRecordTest($record5, 'story', $objectList5)) && p('url') && e('~~');