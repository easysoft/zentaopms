#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDefaultShowGrades();
timeout=0
cid=0

- 步骤3：空数组输入测试 @

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
))) && p() && e('story,requirement,'); // 步骤1：正常单级菜单结构

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => 'story'),
        array('value' => 'requirement')
    )),
    array('items' => array(
        array('value' => 'epic'),
        array('value' => 'feature')
    ))
))) && p() && e('story,requirement,epic,feature,'); // 步骤2：正常多级菜单结构

r($storyTest->getDefaultShowGradesTest(array())) && p() && e(''); // 步骤3：空数组输入测试

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array())
))) && p() && e(''); // 步骤4：单级空items测试

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => 1),
        array('value' => 2),
        array('value' => 3)
    ))
))) && p() && e('1,2,3,'); // 步骤5：数值类型value测试

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => ''),
        array('value' => 'valid_value'),
        array('value' => '0'),
        array('value' => null)
    ))
))) && p() && e(',valid_value,0,,'); // 步骤6：混合数据类型value测试

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
))) && p() && e('level1_item1,level1_item2,level2_item1,level2_item2,level2_item3,level3_item1,'); // 步骤7：复杂多级嵌套结构测试

r($storyTest->getDefaultShowGradesTest(array(
    array(), // 缺少items的异常结构
    array('items' => array(
        array('value' => 'valid_item1')
    )),
    array('other_key' => 'invalid'), // 不符合预期的结构
    array('items' => array(
        array('value' => 'valid_item2')
    ))
))) && p() && e('valid_item1,valid_item2,'); // 步骤8：items不存在的异常结构测试

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array(), // 缺少value的项
        array('value' => 'normal_value'),
        array('other_field' => 'test'), // 不包含value字段
        array('value' => 'another_value')
    ))
))) && p() && e(',normal_value,,another_value,'); // 步骤9：value不存在的异常结构测试

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => 'item1'),
        array('value' => 'item2')
    )),
    array('items' => array(
        array('value' => 'item3'),
        array('value' => 'item4')
    ))
))) && p() && e('item1,item2,item3,item4,'); // 步骤10：批量数据处理测试