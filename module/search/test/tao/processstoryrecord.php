#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processStoryRecord();
timeout=0
cid=18341

- 执行searchTao模块的processStoryRecordTest方法,story不存在于objectList中 >> url为空字符串
- 执行searchTao模块的processStoryRecordTest方法,story类型,lib为0,vision为rnd >> url包含story-storyView-1
- 执行searchTao模块的processStoryRecordTest方法,story类型,lib不为0 >> url包含assetlib-storyView-2
- 执行searchTao模块的processStoryRecordTest方法,requirement类型,lib为0 >> extraType为requirement
- 执行searchTao模块的processStoryRecordTest方法,epic类型,lib为0 >> extraType为epic
- 执行searchTao模块的processStoryRecordTest方法,vision为lite >> url包含projectstory-storyView-6
- 执行searchTao模块的processStoryRecordTest方法,story类型,lib为5,vision为rnd >> url包含assetlib-storyView-7且extraType为story

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('story')->gen(10);

su('admin');

global $config;

$searchTest = new searchTaoTest();

$record1 = new stdClass();
$record1->objectType = 'story';
$record1->objectID = 999;

$record2 = new stdClass();
$record2->objectType = 'story';
$record2->objectID = 1;

$record3 = new stdClass();
$record3->objectType = 'story';
$record3->objectID = 2;

$record4 = new stdClass();
$record4->objectType = 'requirement';
$record4->objectID = 3;

$record5 = new stdClass();
$record5->objectType = 'epic';
$record5->objectID = 4;

$record6 = new stdClass();
$record6->objectType = 'story';
$record6->objectID = 6;

$record7 = new stdClass();
$record7->objectType = 'story';
$record7->objectID = 7;

$story1 = new stdClass();
$story1->lib = 0;
$story1->type = 'story';

$story2 = new stdClass();
$story2->lib = 10;
$story2->type = 'story';

$requirement1 = new stdClass();
$requirement1->lib = 0;
$requirement1->type = 'requirement';

$epic1 = new stdClass();
$epic1->lib = 0;
$epic1->type = 'epic';

$story6 = new stdClass();
$story6->lib = 0;
$story6->type = 'story';

$story7 = new stdClass();
$story7->lib = 5;
$story7->type = 'story';

$objectList1 = array('story' => array());
$objectList2 = array('story' => array(1 => $story1));
$objectList3 = array('story' => array(2 => $story2));
$objectList4 = array('requirement' => array(3 => $requirement1));
$objectList5 = array('epic' => array(4 => $epic1));
$objectList6 = array('story' => array(6 => $story6));
$objectList7 = array('story' => array(7 => $story7));

$config->vision = 'rnd';
r($searchTest->processStoryRecordTest($record1, 'story', $objectList1)) && p('url') && e('');
r($searchTest->processStoryRecordTest($record2, 'story', $objectList2)) && p('url') && e('*/story-storyView-1.html');
r($searchTest->processStoryRecordTest($record3, 'story', $objectList3)) && p('url') && e('*/assetlib-storyView-2.html');
r($searchTest->processStoryRecordTest($record4, 'requirement', $objectList4)) && p('extraType') && e('requirement');
r($searchTest->processStoryRecordTest($record5, 'epic', $objectList5)) && p('extraType') && e('epic');
$config->vision = 'lite';
r($searchTest->processStoryRecordTest($record6, 'story', $objectList6)) && p('url') && e('*/projectstory-storyView-6.html');
$config->vision = 'rnd';
r($searchTest->processStoryRecordTest($record7, 'story', $objectList7)) && p('url,extraType') && e('*/assetlib-storyView-7.html,story');
