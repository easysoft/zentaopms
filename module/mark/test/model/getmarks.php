#!/usr/bin/env php
<?php

/**

title=测试 markModel::getMarks();
timeout=0
cid=17043

- 执行markTest模块的getMarksTest方法，参数是$objectsWithMarks, 'story', 'view'  @2
- 执行markTest模块的getMarksTest方法，参数是$emptyObjects, 'story', 'view'  @0
- 执行markTest模块的getMarksTest方法，参数是$objectsWithMarks, 'story', 'view' 第0条的mark属性 @1
- 执行markTest模块的getMarksTest方法，参数是$objectsWithoutMarks, 'story', 'view' 第0条的mark属性 @~~
- 执行markTest模块的getMarksTest方法，参数是$objectsWithVersionMark, 'story', 'view' 第0条的mark属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mark.unittest.class.php';

$mark = zenData('mark');
$mark->objectType->range('story');
$mark->objectID->range('1{3}, 2{3}, 3{2}');
$mark->version->range('1{3}, 2{3}, 1.0{2}');
$mark->account->range('admin');
$mark->mark->range('view');
$mark->date->range('`2024-01-01 10:00:00`, `2024-01-02 10:00:00`, `2024-01-03 10:00:00`, `2024-01-04 10:00:00`, `2024-01-05 10:00:00`, `2024-01-06 10:00:00`, `2024-01-07 10:00:00`, `2024-01-08 10:00:00`');
$mark->extra->range('');
$mark->gen(8);

su('admin');

$markTest = new markTest();

// 准备测试对象数据
$object1 = new stdclass();
$object1->id = 1;
$object1->version = '1';

$object2 = new stdclass();
$object2->id = 2;
$object2->version = '2';

$object3 = new stdclass();
$object3->id = 3;
$object3->version = '1.0';

$object4 = new stdclass();
$object4->id = 999;
$object4->version = '999';

$objectsWithMarks = array($object1, $object2);
$emptyObjects = array();
$objectsWithoutMarks = array($object4);
$objectsWithVersionMark = array($object3);
$mixedObjects = array($object1, $object4);

r(count($markTest->getMarksTest($objectsWithMarks, 'story', 'view'))) && p() && e('2');
r(count($markTest->getMarksTest($emptyObjects, 'story', 'view'))) && p() && e('0');
r($markTest->getMarksTest($objectsWithMarks, 'story', 'view')) && p('0:mark') && e('1');
r($markTest->getMarksTest($objectsWithoutMarks, 'story', 'view')) && p('0:mark') && e('~~');
r($markTest->getMarksTest($objectsWithVersionMark, 'story', 'view')) && p('0:mark') && e('1');