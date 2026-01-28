#!/usr/bin/env php
<?php

/**

title=测试 blockZen::buildProjectStatistic();
timeout=0
cid=15237

- 步骤1：敏捷项目costs统计属性costs @100
- 步骤2：敏捷项目故事点统计属性storyPoints @50
- 步骤3：瀑布项目PV统计属性pv @85.50
- 步骤4：无统计数据处理属性costs @0
- 步骤5：无限期项目风险统计属性risks @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（不需要数据库数据，直接创建对象）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 5. 准备测试数据
// 敏捷项目对象
$scrumProject = new stdclass();
$scrumProject->id = 1;
$scrumProject->model = 'scrum';
$scrumProject->end = '2024-12-31';

// 看板项目对象
$kanbanProject = new stdclass();
$kanbanProject->id = 2;
$kanbanProject->model = 'kanban';
$kanbanProject->end = '2024-12-31';

// 瀑布项目对象
$waterfallProject = new stdclass();
$waterfallProject->id = 3;
$waterfallProject->model = 'waterfall';
$waterfallProject->end = '2024-12-31';

// 无限期项目对象
$longTimeProject = new stdclass();
$longTimeProject->id = 4;
$longTimeProject->model = 'scrum';
$longTimeProject->end = LONG_TIME;

// 准备统计数据
$statisticData = array(
    'investedGroup' => array(1 => array('value' => 100.0)),
    'consumeTaskGroup' => array(1 => array('value' => 80.0)),
    'leftTaskGroup' => array(1 => array('value' => 20.0)),
    'countStoryGroup' => array(1 => array('value' => 50)),
    'finishedStoryGroup' => array(1 => array('value' => 30)),
    'unclosedStoryGroup' => array(1 => array('value' => 20)),
    'countTaskGroup' => array(1 => array('value' => 100)),
    'waitTaskGroup' => array(1 => array('value' => 10)),
    'doingTaskGroup' => array(1 => array('value' => 15)),
    'countBugGroup' => array(1 => array('value' => 25)),
    'closedBugGroup' => array(1 => array('value' => 20)),
    'activatedBugGroup' => array(1 => array('value' => 5)),
    'PVGroup' => array(3 => array('value' => 85.5)),
    'EVGroup' => array(3 => array('value' => 75.2)),
    'ACGroup' => array(3 => array('value' => 90.3)),
    'SVGroup' => array(3 => array('value' => -0.1208)),
    'CVGroup' => array(3 => array('value' => -0.2006)),
    'riskCountGroup' => array(1 => array('value' => 3), 2 => array('value' => 1), 3 => array('value' => 2)),
    'issueCountGroup' => array(1 => array('value' => 2), 2 => array('value' => 0), 3 => array('value' => 1))
);

$emptyData = array();

// 6. 强制要求：必须包含至少5个测试步骤
r($blockTest->buildProjectStatisticTest($scrumProject, $statisticData)) && p('costs') && e('100'); // 步骤1：敏捷项目costs统计
r($blockTest->buildProjectStatisticTest($scrumProject, $statisticData)) && p('storyPoints') && e('50'); // 步骤2：敏捷项目故事点统计
r($blockTest->buildProjectStatisticTest($waterfallProject, $statisticData)) && p('pv') && e('85.50'); // 步骤3：瀑布项目PV统计
r($blockTest->buildProjectStatisticTest($scrumProject, $emptyData)) && p('costs') && e('0'); // 步骤4：无统计数据处理
r($blockTest->buildProjectStatisticTest($longTimeProject, $statisticData)) && p('risks') && e('0'); // 步骤5：无限期项目风险统计