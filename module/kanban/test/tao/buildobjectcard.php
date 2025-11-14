#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::buildObjectCard();
timeout=0
cid=16973

- 执行kanbanTest模块的buildObjectCardTest方法，参数是$objectCard1, $object1, 'productplan', $creators1 属性createdBy @admin
- 执行kanbanTest模块的buildObjectCardTest方法，参数是$objectCard2, $object2, 'release', $creators2 属性createdBy @user1
- 执行kanbanTest模块的buildObjectCardTest方法，参数是$objectCard3, $object3, 'execution', $creators3 
 - 属性execType @sprint
 - 属性progress @30
- 执行kanbanTest模块的buildObjectCardTest方法，参数是$objectCard4, $object4, 'productplan', $creators4 属性createdBy @~~
- 执行kanbanTest模块的buildObjectCardTest方法，参数是$objectCard5, $object5, 'release', $creators5 
 - 属性createdBy @tester
 - 属性status @done

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

zenData('kanbancard')->gen(0);

su('admin');

$kanbanTest = new kanbanTest();

// 准备测试数据
$objectCard1 = new stdclass();
$objectCard1->name = 'Test Card 1';
$objectCard1->desc = '<p>Test description</p>';
$objectCard1->status = 'doing';
$objectCard1->progress = 50;
$objectCard1->end = date('Y-m-d', strtotime('+30 days'));

$object1 = new stdclass();
$object1->id = 1;
$object1->name = 'Test Object 1';
$object1->desc = '<p>Test object description</p>';
$object1->status = 'active';

$creators1 = array(1 => 'admin');

$objectCard2 = new stdclass();
$objectCard2->name = 'Test Card 2';
$objectCard2->desc = '<p>Release card</p>';
$objectCard2->status = 'doing';
$objectCard2->progress = 80;
$objectCard2->end = date('Y-m-d', strtotime('+60 days'));

$object2 = new stdclass();
$object2->id = 2;
$object2->name = 'Test Release';
$object2->desc = '<p>Release description</p>';
$object2->status = 'active';

$creators2 = array(2 => 'user1');

$objectCard3 = new stdclass();
$objectCard3->name = 'Execution Card';
$objectCard3->desc = '<p>Execution description</p>';
$objectCard3->status = 'doing';
$objectCard3->progress = 30;
$objectCard3->end = date('Y-m-d', strtotime('+90 days'));

$object3 = new stdclass();
$object3->id = 3;
$object3->name = 'Test Execution';
$object3->desc = '<p><strong>Execution</strong> with HTML</p>';
$object3->status = 'wait';
$object3->type = 'sprint';
$object3->progress = 30;
$object3->end = date('Y-m-d', strtotime('+90 days'));
$object3->path = ',1,2,3,';

$creators3 = array();

$objectCard4 = new stdclass();
$objectCard4->name = 'Empty Creators Card';
$objectCard4->desc = 'Simple description';
$objectCard4->status = 'doing';
$objectCard4->progress = 0;
$objectCard4->end = date('Y-m-d', strtotime('+15 days'));

$object4 = new stdclass();
$object4->id = 4;
$object4->name = 'Empty Creators Object';
$object4->desc = 'Simple object description';
$object4->status = 'active';

$creators4 = array();

$objectCard5 = new stdclass();
$objectCard5->name = 'HTML Description Card';
$objectCard5->desc = '<div><p>Complex HTML <b>content</b> with <i>tags</i></p></div>';
$objectCard5->status = 'doing';
$objectCard5->progress = 100;
$objectCard5->end = date('Y-m-d', strtotime('+45 days'));

$object5 = new stdclass();
$object5->id = 5;
$object5->name = 'HTML Object';
$object5->desc = '<div><p>Complex HTML <b>object</b> description with <i>formatting</i></p></div>';
$object5->status = 'done';

$creators5 = array(5 => 'tester');

r($kanbanTest->buildObjectCardTest($objectCard1, $object1, 'productplan', $creators1)) && p('createdBy') && e('admin');
r($kanbanTest->buildObjectCardTest($objectCard2, $object2, 'release', $creators2)) && p('createdBy') && e('user1');
r($kanbanTest->buildObjectCardTest($objectCard3, $object3, 'execution', $creators3)) && p('execType,progress') && e('sprint,30');
r($kanbanTest->buildObjectCardTest($objectCard4, $object4, 'productplan', $creators4)) && p('createdBy') && e('~~');
r($kanbanTest->buildObjectCardTest($objectCard5, $object5, 'release', $creators5)) && p('createdBy,status') && e('tester,done');