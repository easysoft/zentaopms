#!/usr/bin/env php
<?php

/**

title=测试 screenModel::genNotFoundOrDraftComponentOption();
timeout=0
cid=0

- 执行screenTest模块的genNotFoundOrDraftComponentOptionTest方法，参数是$component1, $chart1, 'chart' 属性notFoundText @图表 测试图表 未找到或处于草稿状态
- 执行screenTest模块的genNotFoundOrDraftComponentOptionTest方法，参数是$component2, $chart2, 'pivot' 属性notFoundText @透视表 测试透视表 未找到或处于草稿状态
- 执行screenTest模块的genNotFoundOrDraftComponentOptionTest方法，参数是$component3, $chart3, 'chart'
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性isDeleted @1
- 执行screenTest模块的genNotFoundOrDraftComponentOptionTest方法，参数是$component4, $chart4, 'chart' 属性notFoundText @图表  未找到或处于草稿状态
- 执行screenTest模块的genNotFoundOrDraftComponentOptionTest方法，参数是$component5, $chart5, 'chart'
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性isDeleted @1
- 执行screenTest模块的genNotFoundOrDraftComponentOptionTest方法，参数是$component6, $chart6, 'pivot'
 - 属性hasNotFoundText @1
 - 属性isDeleted @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

su('admin');

$screenTest = new screenTest();

// 准备测试数据
$component1 = new stdclass();
$chart1 = new stdclass();
$chart1->name = '测试图表';

$component2 = new stdclass();
$chart2 = new stdclass();
$chart2->name = '测试透视表';

$component3 = null;
$chart3 = new stdclass();
$chart3->name = '空组件测试';

$component4 = new stdclass();
$chart4 = new stdclass();

$component5 = new stdclass();
$component5->option = new stdclass();
$component5->option->existingProp = 'keep';
$chart5 = new stdclass();
$chart5->name = '已有结构';

$component6 = new stdclass();
$chart6 = new stdclass();

r($screenTest->genNotFoundOrDraftComponentOptionTest($component1, $chart1, 'chart')) && p('notFoundText') && e('图表 测试图表 未找到或处于草稿状态');
r($screenTest->genNotFoundOrDraftComponentOptionTest($component2, $chart2, 'pivot')) && p('notFoundText') && e('透视表 测试透视表 未找到或处于草稿状态');
r($screenTest->genNotFoundOrDraftComponentOptionTest($component3, $chart3, 'chart')) && p('hasOption,hasTitle,isDeleted') && e('1,1,1');
r($screenTest->genNotFoundOrDraftComponentOptionTest($component4, $chart4, 'chart')) && p('notFoundText') && e('图表  未找到或处于草稿状态');
r($screenTest->genNotFoundOrDraftComponentOptionTest($component5, $chart5, 'chart')) && p('hasOption,hasTitle,isDeleted') && e('1,1,1');
r($screenTest->genNotFoundOrDraftComponentOptionTest($component6, $chart6, 'pivot')) && p('hasNotFoundText,isDeleted') && e('1,1');