#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::setNewMark();
timeout=0
cid=17465

- 执行$result1属性name @Test Pivot 1
- 执行$result2属性name @Test Pivot 2
- 执行$result3属性name @Test Pivot 3
- 执行$result4属性mark @1
- 执行$result5->name @1
- 执行$result6属性name @Test Pivot 6
- 执行$result7属性name @Test Pivot 7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

su('admin');

$pivotTest = new pivotZenTest();

$firstAction = new stdclass();
$firstAction->date = '2024-01-01 00:00:00';

$builtins = array(1 => array('id' => 1));

// 场景1: 非内置透视表
$pivot1 = new stdclass();
$pivot1->id = 1;
$pivot1->name = 'Test Pivot 1';
$pivot1->builtin = 0;
$pivot1->version = '1.0';
$pivot1->versionChange = false;
$pivot1->mark = false;
$pivot1->createdDate = '2023-12-01 00:00:00';

// 场景2: 内置透视表但版本未改变且不在builtins列表中
$pivot2 = new stdclass();
$pivot2->id = 2;
$pivot2->name = 'Test Pivot 2';
$pivot2->builtin = 1;
$pivot2->version = '1.0';
$pivot2->versionChange = false;
$pivot2->mark = false;
$pivot2->createdDate = '2023-12-01 00:00:00';

// 场景3: 内置透视表,版本未改变,在builtins列表中,且已有mark
$pivot3 = new stdclass();
$pivot3->id = 1;
$pivot3->name = 'Test Pivot 3';
$pivot3->builtin = 1;
$pivot3->version = '1.0';
$pivot3->versionChange = false;
$pivot3->mark = true;
$pivot3->createdDate = '2023-12-01 00:00:00';

// 场景4: 内置透视表,版本未改变,在builtins列表中,无mark且创建时间早于firstAction
$pivot4 = new stdclass();
$pivot4->id = 1;
$pivot4->name = 'Test Pivot 4';
$pivot4->builtin = 1;
$pivot4->version = '1.0';
$pivot4->versionChange = false;
$pivot4->mark = false;
$pivot4->createdDate = '2023-06-01 00:00:00';

// 场景5: 内置透视表,版本未改变,在builtins列表中,无mark且版本是主版本
$pivot5 = new stdclass();
$pivot5->id = 1;
$pivot5->name = 'Test Pivot 5';
$pivot5->builtin = 1;
$pivot5->version = '2';
$pivot5->versionChange = false;
$pivot5->mark = false;
$pivot5->createdDate = '2024-06-01 00:00:00';

// 场景6: 内置透视表,版本未改变,在builtins列表中,无mark且版本非主版本
$pivot6 = new stdclass();
$pivot6->id = 1;
$pivot6->name = 'Test Pivot 6';
$pivot6->builtin = 1;
$pivot6->version = '2.0';
$pivot6->versionChange = false;
$pivot6->mark = false;
$pivot6->createdDate = '2024-06-01 00:00:00';

// 场景7: 内置透视表,版本未改变,在builtins列表中,有mark且版本是主版本
$pivot7 = new stdclass();
$pivot7->id = 1;
$pivot7->name = 'Test Pivot 7';
$pivot7->builtin = 1;
$pivot7->version = '3';
$pivot7->versionChange = false;
$pivot7->mark = true;
$pivot7->createdDate = '2024-06-01 00:00:00';

$result1 = $pivotTest->setNewMarkTest($pivot1, $firstAction, $builtins);
$result2 = $pivotTest->setNewMarkTest($pivot2, $firstAction, $builtins);
$result3 = $pivotTest->setNewMarkTest($pivot3, $firstAction, $builtins);
$result4 = $pivotTest->setNewMarkTest($pivot4, $firstAction, $builtins);
$result5 = $pivotTest->setNewMarkTest($pivot5, $firstAction, $builtins);
$result6 = $pivotTest->setNewMarkTest($pivot6, $firstAction, $builtins);
$result7 = $pivotTest->setNewMarkTest($pivot7, $firstAction, $builtins);

r($result1) && p('name') && e('Test Pivot 1');
r($result2) && p('name') && e('Test Pivot 2');
r($result3) && p('name') && e('Test Pivot 3');
r($result4) && p('mark') && e('1');
r(is_array($result5->name)) && p() && e('1');
r($result6) && p('name') && e('Test Pivot 6');
r($result7) && p('name') && e('Test Pivot 7');