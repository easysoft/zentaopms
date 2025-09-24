#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDefaultShowGrades();
timeout=0
cid=0

- 步骤3：边界值测试空数组 @

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => 'story'),
        array('value' => 'requirement')
    ))
))) && p() && e('story,requirement,'); // 步骤1：正常情况测试单级菜单

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => 'story'),
        array('value' => 'requirement')
    )),
    array('items' => array(
        array('value' => 'epic'),
        array('value' => 'feature')
    ))
))) && p() && e('story,requirement,epic,feature,'); // 步骤2：正常情况测试多级菜单

r($storyTest->getDefaultShowGradesTest(array())) && p() && e(''); // 步骤3：边界值测试空数组

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array())
))) && p() && e(''); // 步骤4：边界值测试空items数组

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => 1),
        array('value' => 2),
        array('value' => 3)
    ))
))) && p() && e('1,2,3,'); // 步骤5：测试包含数字值的菜单

r($storyTest->getDefaultShowGradesTest(array(
    array('items' => array(
        array('value' => ''),
        array('value' => 'valid_value'),
        array('value' => '0'),
        array('value' => null)
    ))
))) && p() && e(',valid_value,0,,'); // 步骤6：测试包含空字符串和null value的菜单

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
))) && p() && e('level1_item1,level1_item2,level2_item1,level2_item2,level2_item3,level3_item1,'); // 步骤7：测试复杂多级嵌套菜单