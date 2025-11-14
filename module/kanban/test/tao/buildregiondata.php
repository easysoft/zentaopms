#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::buildRegionData();
timeout=0
cid=16975

- 测试步骤1：正常构造区域数据属性laneCount @2
- 测试步骤2：空分组数据输入属性laneCount @0
- 测试步骤3：只有分组但无泳道的数据属性laneCount @0
- 测试步骤4：复杂数据结构处理属性laneCount @3
- 测试步骤5：测试卡片数量统计功能属性laneCount @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 4. 测试步骤1：正常构造区域数据
$regionData1 = array('items' => array());
$groups1 = array();
$group1 = new stdClass();
$group1->id = 1;
$groups1[] = $group1;

$laneGroup1 = array(
    1 => array(
        array('id' => 1, 'name' => 'Lane 1'),
        array('id' => 2, 'name' => 'Lane 2')
    )
);

$columnGroup1 = array(
    1 => array(
        array('id' => 1, 'name' => 'Column 1', 'parent' => 0),
        array('id' => 2, 'name' => 'Column 2', 'parent' => 1)
    )
);

$cardGroup1 = array(
    1 => array(
        1 => array(
            1 => array('card1', 'card2'),
            2 => array('card3')
        )
    )
);

// 5. 测试步骤2：空分组数据输入
$regionData2 = array('items' => array());
$groups2 = array();
$laneGroup2 = array();
$columnGroup2 = array();
$cardGroup2 = array();

// 6. 测试步骤3：只有分组但无泳道的数据
$regionData3 = array('items' => array());
$groups3 = array();
$group3 = new stdClass();
$group3->id = 1;
$groups3[] = $group3;

$laneGroup3 = array(1 => array()); // 空泳道数组
$columnGroup3 = array();
$cardGroup3 = array();

// 7. 测试步骤4：复杂数据结构处理
$regionData4 = array('items' => array());
$groups4 = array();
$group4a = new stdClass();
$group4a->id = 1;
$group4b = new stdClass();
$group4b->id = 2;
$groups4[] = $group4a;
$groups4[] = $group4b;

$laneGroup4 = array(
    1 => array(
        array('id' => 1, 'name' => 'Lane 1'),
        array('id' => 2, 'name' => 'Lane 2')
    ),
    2 => array(
        array('id' => 3, 'name' => 'Lane 3')
    )
);

$columnGroup4 = array(
    1 => array(
        array('id' => 1, 'name' => 'Column 1', 'parent' => 0),
        array('id' => 2, 'name' => 'Column 2', 'parent' => 1),
        array('id' => 3, 'name' => 'Column 3', 'parent' => 1)
    ),
    2 => array(
        array('id' => 4, 'name' => 'Column 4', 'parent' => 0)
    )
);

$cardGroup4 = array(
    1 => array(
        1 => array(
            2 => array('card1', 'card2'),
            3 => array('card3', 'card4', 'card5')
        )
    ),
    2 => array(
        3 => array(
            4 => array('card6')
        )
    )
);

// 8. 测试步骤5：测试卡片数量统计功能
$regionData5 = array('items' => array());
$groups5 = array();
$group5 = new stdClass();
$group5->id = 1;
$groups5[] = $group5;

$laneGroup5 = array(
    1 => array(
        array('id' => 1, 'name' => 'Lane 1')
    )
);

$columnGroup5 = array(
    1 => array(
        array('id' => 1, 'name' => 'Parent Column', 'parent' => 0),
        array('id' => 2, 'name' => 'Child Column 1', 'parent' => 1),
        array('id' => 3, 'name' => 'Child Column 2', 'parent' => 1)
    )
);

$cardGroup5 = array(
    1 => array(
        1 => array(
            2 => array('card1', 'card2'),
            3 => array('card3', 'card4', 'card5')
        )
    )
);

// 执行测试步骤
r($kanbanTest->buildRegionDataTest($regionData1, $groups1, $laneGroup1, $columnGroup1, $cardGroup1)) && p('laneCount') && e('2'); // 测试步骤1：正常构造区域数据
r($kanbanTest->buildRegionDataTest($regionData2, $groups2, $laneGroup2, $columnGroup2, $cardGroup2)) && p('laneCount') && e('0'); // 测试步骤2：空分组数据输入
r($kanbanTest->buildRegionDataTest($regionData3, $groups3, $laneGroup3, $columnGroup3, $cardGroup3)) && p('laneCount') && e('0'); // 测试步骤3：只有分组但无泳道的数据
r($kanbanTest->buildRegionDataTest($regionData4, $groups4, $laneGroup4, $columnGroup4, $cardGroup4)) && p('laneCount') && e('3'); // 测试步骤4：复杂数据结构处理
r($kanbanTest->buildRegionDataTest($regionData5, $groups5, $laneGroup5, $columnGroup5, $cardGroup5)) && p('laneCount') && e('1'); // 测试步骤5：测试卡片数量统计功能