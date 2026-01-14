#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDefaultShowGrades();
timeout=0
cid=18526

- 执行storyTest模块的getDefaultShowGradesTest方法，参数是$testData1  @story,requirement,

- 执行storyTest模块的getDefaultShowGradesTest方法，参数是$testData2  @story,requirement,epic,feature,

- 执行storyTest模块的getDefaultShowGradesTest方法，参数是$testData3  @0
- 执行storyTest模块的getDefaultShowGradesTest方法，参数是$testData4  @0
- 执行storyTest模块的getDefaultShowGradesTest方法，参数是$testData5  @1,2,3,

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$storyTest = new storyModelTest();

$testData1 = array(
    array('items' => array(
        array('value' => 'story'),
        array('value' => 'requirement')
    ))
);
r($storyTest->getDefaultShowGradesTest($testData1)) && p() && e('story,requirement,');

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
r($storyTest->getDefaultShowGradesTest($testData2)) && p() && e('story,requirement,epic,feature,');

$testData3 = array();
r($storyTest->getDefaultShowGradesTest($testData3)) && p() && e('0');

$testData4 = array(
    array('items' => array())
);
r($storyTest->getDefaultShowGradesTest($testData4)) && p() && e('0');

$testData5 = array(
    array('items' => array(
        array('value' => 1),
        array('value' => 2),
        array('value' => 3)
    ))
);
r($storyTest->getDefaultShowGradesTest($testData5)) && p() && e('1,2,3,');