#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::buildRDRegionData();
timeout=0
cid=16974

- 步骤1：正常情况测试属性laneCount @2
- 步骤2：空分组测试属性laneCount @0
- 步骤3：parentStory类型泳道测试属性laneCount @1
- 步骤4：story类型泳道测试属性laneCount @1
- 步骤5：搜索无匹配项测试属性laneCount @0
- 步骤6：父子列计数测试属性laneCount @1
- 步骤7：复杂数据结构测试属性laneCount @4

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTaoTest();

// 4. 测试步骤

// 步骤1：正常情况下构建RD看板区域数据
$regionData = array('items' => array(), 'laneCount' => 0, 'links' => array());
$groups = array(
    (object)array('id' => 1),
    (object)array('id' => 2)
);
$laneGroup = array(
    1 => array(
        (array)array('id' => 1, 'type' => 'story', 'name' => 'Story Lane')
    ),
    2 => array(
        (array)array('id' => 2, 'type' => 'task', 'name' => 'Task Lane')
    )
);
$columnGroup = array(
    1 => array(
        array('id' => 1, 'parent' => 0, 'name' => 'To Do'),
        array('id' => 2, 'parent' => 1, 'name' => 'In Progress')
    ),
    2 => array(
        array('id' => 3, 'parent' => 0, 'name' => 'Done')
    )
);
$cardGroup = array(
    1 => array(
        'lane1' => array(
            1 => array(
                array('id' => 1, 'parent' => 0),
                array('id' => 2, 'parent' => 0)
            )
        )
    )
);
r($kanbanTest->buildRDRegionDataTest($regionData, $groups, $laneGroup, $columnGroup, $cardGroup)) && p('laneCount') && e(2); // 步骤1：正常情况测试

// 步骤2：空分组情况下构建数据
$emptyGroups = array();
$emptyLaneGroup = array();
$emptyColumnGroup = array();
$emptyCardGroup = array();
r($kanbanTest->buildRDRegionDataTest($regionData, $emptyGroups, $emptyLaneGroup, $emptyColumnGroup, $emptyCardGroup)) && p('laneCount') && e(0); // 步骤2：空分组测试

// 步骤3：包含parentStory类型泳道的情况
$parentStoryGroups = array((object)array('id' => 1));
$parentStoryLaneGroup = array(
    1 => array(
        array('id' => 1, 'type' => 'parentStory', 'name' => 'Parent Story Lane')
    )
);
$parentStoryColumnGroup = array(
    1 => array(
        array('id' => 1, 'parent' => 0, 'name' => 'To Do')
    )
);
$parentStoryCardGroup = array(
    1 => array(
        'lane1' => array(
            1 => array(array('id' => 1, 'parent' => 0))
        )
    )
);
$result3 = $kanbanTest->buildRDRegionDataTest($regionData, $parentStoryGroups, $parentStoryLaneGroup, $parentStoryColumnGroup, $parentStoryCardGroup);
r($result3) && p('laneCount') && e(1); // 步骤3：parentStory类型泳道测试

// 步骤4：包含story类型泳道的情况
$storyGroups = array((object)array('id' => 2));
$storyLaneGroup = array(
    2 => array(
        array('id' => 2, 'type' => 'story', 'name' => 'Story Lane')
    )
);
$storyColumnGroup = array(
    2 => array(
        array('id' => 2, 'parent' => 0, 'name' => 'In Progress')
    )
);
$storyCardGroup = array(
    2 => array(
        'lane2' => array(
            2 => array(
                array('id' => 2, 'parent' => 1),
                array('id' => 3, 'parent' => 1)
            )
        )
    )
);
$result4 = $kanbanTest->buildRDRegionDataTest($regionData, $storyGroups, $storyLaneGroup, $storyColumnGroup, $storyCardGroup);
r($result4) && p('laneCount') && e(1); // 步骤4：story类型泳道测试

// 步骤5：包含搜索条件但无匹配卡片的情况
$searchValue = 'nonexistent';
$groupsWithNoCards = array((object)array('id' => 3));
$laneGroupWithNoCards = array(
    3 => array(
        array('id' => 3, 'type' => 'bug', 'name' => 'Bug Lane')
    )
);
$columnGroupWithNoCards = array(
    3 => array(
        array('id' => 3, 'parent' => 0, 'name' => 'Fixed')
    )
);
$emptyCardGroupForSearch = array(); // 空卡片组
$result5 = $kanbanTest->buildRDRegionDataTest($regionData, $groupsWithNoCards, $laneGroupWithNoCards, $columnGroupWithNoCards, $emptyCardGroupForSearch, $searchValue);
r($result5) && p('laneCount') && e(0); // 步骤5：搜索无匹配项测试

// 步骤6：父子列卡片计数测试
$countGroups = array((object)array('id' => 4));
$countLaneGroup = array(
    4 => array(
        array('id' => 4, 'type' => 'task', 'name' => 'Count Test Lane')
    )
);
$countColumnGroup = array(
    4 => array(
        array('id' => 10, 'parent' => 0, 'name' => 'Parent Column'),
        array('id' => 11, 'parent' => 10, 'name' => 'Child Column')
    )
);
$countCardGroup = array(
    4 => array(
        'lane4' => array(
            11 => array(
                array('id' => 10, 'parent' => 0),
                array('id' => 11, 'parent' => 0),
                array('id' => 12, 'parent' => 0)
            )
        )
    )
);
$result6 = $kanbanTest->buildRDRegionDataTest($regionData, $countGroups, $countLaneGroup, $countColumnGroup, $countCardGroup);
r($result6) && p('laneCount') && e(1); // 步骤6：父子列计数测试

// 步骤7：复杂数据结构测试
$complexGroups = array(
    (object)array('id' => 5),
    (object)array('id' => 6)
);
$complexLaneGroup = array(
    5 => array(
        array('id' => 5, 'type' => 'parentStory', 'name' => 'Parent Story'),
        array('id' => 6, 'type' => 'story', 'name' => 'Story')
    ),
    6 => array(
        array('id' => 7, 'type' => 'task', 'name' => 'Task'),
        array('id' => 8, 'type' => 'bug', 'name' => 'Bug')
    )
);
$complexColumnGroup = array(
    5 => array(
        array('id' => 20, 'parent' => 0, 'name' => 'Backlog'),
        array('id' => 21, 'parent' => 0, 'name' => 'Active')
    ),
    6 => array(
        array('id' => 22, 'parent' => 0, 'name' => 'To Do'),
        array('id' => 23, 'parent' => 22, 'name' => 'Doing'),
        array('id' => 24, 'parent' => 0, 'name' => 'Done')
    )
);
$complexCardGroup = array(
    5 => array(
        'lane5' => array(
            20 => array(array('id' => 20, 'parent' => 0))
        ),
        'lane6' => array(
            21 => array(array('id' => 21, 'parent' => 20))
        )
    ),
    6 => array(
        'lane7' => array(
            22 => array(array('id' => 22, 'parent' => 0))
        )
    )
);
$result7 = $kanbanTest->buildRDRegionDataTest($regionData, $complexGroups, $complexLaneGroup, $complexColumnGroup, $complexCardGroup);
r($result7) && p('laneCount') && e(4); // 步骤7：复杂数据结构测试