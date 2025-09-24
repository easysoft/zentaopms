#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDefaultShowGrades();
timeout=0
cid=0

- 执行storyTest模块的getDefaultShowGradesTest方法，参数是array  @

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
))) && p() && e('story,requirement,');

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => 'story'),
        array('value' => 'requirement')
    )),
    array('items' => array(
        array('value' => 'epic'),
        array('value' => 'feature')
    ))
))) && p() && e('story,requirement,epic,feature,');

r($storyTest->getDefaultShowGradesTest(array())) && p() && e('');

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array())
))) && p() && e('');

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => 1),
        array('value' => 2),
        array('value' => 3)
    ))
))) && p() && e('1,2,3,');

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => ''),
        array('value' => 'valid_value'),
        array('value' => '0'),
        array('value' => null)
    ))
))) && p() && e(',valid_value,0,,');

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => 'level1_item1'),
        array('value' => 'level1_item2')
    )),
    array('items' => array(
        array('value' => 'level2_item1'),
        array('value' => 'level2_item2'),
        array('value' => 'level2_item3')
    )),
    array('items' => array(
        array('value' => 'level3_item1')
    ))
))) && p() && e('level1_item1,level1_item2,level2_item1,level2_item2,level2_item3,level3_item1,');