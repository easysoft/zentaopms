#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getMergeTrackCells();
timeout=0
cid=18544

- 步骤1：正常情况第lane2条的demand_col1属性 @1
- 步骤2：空数据 @0
- 步骤3：无匹配类型 @0
- 步骤4：多种类型第lane2条的story_col1属性 @1
- 步骤5：复杂结构 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$storyTest = new storyModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：正常tracks数据和showCols参数
$tracks1 = array(
    'lanes' => array(
        array('name' => 'lane1'),
        array('name' => 'lane2')
    ),
    'cols' => array(
        array('name' => 'demand_col1', 'parent' => 1),
        array('name' => 'epic_col1', 'parent' => 2),
        array('name' => 'requirement_col1', 'parent' => 3),
        array('name' => 'story_col1', 'parent' => 4)
    ),
    'items' => array(
        'lane1' => array(
            'demand_col1' => array((object)array('id' => 1)),
            'epic_col1' => array((object)array('id' => 2))
        ),
        'lane2' => array(
            'demand_col1' => array((object)array('id' => 1)),
            'epic_col1' => array((object)array('id' => 3))
        )
    )
);
$showCols1 = array('demand', 'epic', 'requirement', 'story');
r($storyTest->getMergeTrackCellsTest($tracks1, $showCols1)) && p('lane2:demand_col1') && e('1'); // 步骤1：正常情况

// 测试步骤2：空tracks数据
$tracks2 = array();
$showCols2 = array('story', 'requirement');
r($storyTest->getMergeTrackCellsTest($tracks2, $showCols2)) && p() && e('0'); // 步骤2：空数据

// 测试步骤3：无匹配的showCols类型
$tracks3 = array(
    'lanes' => array(
        array('name' => 'lane1')
    ),
    'cols' => array(
        array('name' => 'other_col1', 'parent' => 1),
        array('name' => 'another_col1', 'parent' => 2)
    ),
    'items' => array(
        'lane1' => array(
            'other_col1' => array((object)array('id' => 1))
        )
    )
);
$showCols3 = array('story', 'requirement');
r($storyTest->getMergeTrackCellsTest($tracks3, $showCols3)) && p() && e('0'); // 步骤3：无匹配类型

// 测试步骤4：包含多种story类型的showCols
$tracks4 = array(
    'lanes' => array(
        array('name' => 'lane1'),
        array('name' => 'lane2')
    ),
    'cols' => array(
        array('name' => 'story_col1', 'parent' => 1),
        array('name' => 'requirement_col1', 'parent' => 2),
        array('name' => 'epic_col1', 'parent' => 3)
    ),
    'items' => array(
        'lane1' => array(
            'story_col1' => array((object)array('id' => 1)),
            'requirement_col1' => array((object)array('id' => 2)),
            'epic_col1' => array((object)array('id' => 3))
        ),
        'lane2' => array(
            'story_col1' => array((object)array('id' => 1)),
            'requirement_col1' => array((object)array('id' => 4)),
            'epic_col1' => array((object)array('id' => 5))
        )
    )
);
$showCols4 = array('story', 'requirement', 'epic');
r($storyTest->getMergeTrackCellsTest($tracks4, $showCols4)) && p('lane2:story_col1') && e('1'); // 步骤4：多种类型

// 测试步骤5：复杂tracks结构测试
$tracks5 = array(
    'lanes' => array(
        array('name' => 'lane1'),
        array('name' => 'lane2'),
        array('name' => 'lane3')
    ),
    'cols' => array(
        array('name' => 'story_col1', 'parent' => 1),
        array('name' => 'story_col2', 'parent' => 2),
        array('name' => 'requirement_col1', 'parent' => 3)
    ),
    'items' => array(
        'lane1' => array(
            'story_col1' => array((object)array('id' => 1)),
            'story_col2' => array((object)array('id' => 2)),
            'requirement_col1' => array((object)array('id' => 3))
        ),
        'lane2' => array(
            'story_col1' => array((object)array('id' => 1)),
            'story_col2' => array((object)array('id' => 2)),
            'requirement_col1' => array((object)array('id' => 3))
        ),
        'lane3' => array(
            'story_col1' => array((object)array('id' => 4)),
            'story_col2' => array((object)array('id' => 5)),
            'requirement_col1' => array((object)array('id' => 6))
        )
    )
);
$showCols5 = array('story', 'requirement');
r(count($storyTest->getMergeTrackCellsTest($tracks5, $showCols5))) && p() && e('1'); // 步骤5：复杂结构