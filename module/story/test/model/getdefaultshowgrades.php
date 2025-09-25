#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDefaultShowGrades();
timeout=0
cid=0

- 测试步骤3：空数组输入测试 @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

su('admin');

$storyTest = new storyTest();

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => 'story'),
        array('value' => 'requirement')
    ))
))) && p() && e('story,requirement,'); // 测试步骤1：正常单级菜单结构输入

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => 'story'),
        array('value' => 'requirement')
    )),
    array('items' => array(
        array('value' => 'epic'),
        array('value' => 'feature')
    ))
))) && p() && e('story,requirement,epic,feature,'); // 测试步骤2：多级菜单结构输入

r($storyTest->getDefaultShowGradesTest(array())) && p() && e(''); // 测试步骤3：空数组输入测试

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array())
))) && p() && e(''); // 测试步骤4：单级空items测试

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => 1),
        array('value' => 2),
        array('value' => 3)
    ))
))) && p() && e('1,2,3,'); // 测试步骤5：数值类型value测试