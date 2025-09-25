#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDefaultShowGrades();
timeout=0
cid=0

- 测试步骤1：正常单级菜单结构输入 @story,requirement,

- 测试步骤2：多级菜单结构输入 @story,requirement,epic,feature,

- 测试步骤3：空数组输入测试 @
- 测试步骤4：单级空items测试 @
- 测试步骤5：数值类型value测试 @1,2,3,

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

su('admin');

$storyTest = new storyTest();

$testData1 = array(
    array('items' => array(
        array('value' => 'story'),
        array('value' => 'requirement')
    ))
);
r($storyTest->getDefaultShowGradesTest($testData1)) && p() && e('story,requirement,'); // 测试步骤1：正常单级菜单结构输入

$testData2 = array(
    array('items' => array(
        array('value' => 'story'),
        array('value' => 'requirement')
    )),
    array('items' => array(
        array('value' => 'epic'),
        array('value' => 'feature')
    ))
);
r($storyTest->getDefaultShowGradesTest($testData2)) && p() && e('story,requirement,epic,feature,'); // 测试步骤2：多级菜单结构输入

$testData3 = array();
r($storyTest->getDefaultShowGradesTest($testData3)) && p() && e(''); // 测试步骤3：空数组输入测试

$testData3 = array(
    array('items' => array())
);
r($storyTest->getDefaultShowGradesTest($testData3)) && p() && e(''); // 测试步骤4：单级空items测试

$testData5 = array(
    array('items' => array(
        array('value' => 1),
        array('value' => 2),
        array('value' => 3)
    ))
);
r($storyTest->getDefaultShowGradesTest($testData5)) && p() && e('1,2,3,'); // 测试步骤5：数值类型value测试