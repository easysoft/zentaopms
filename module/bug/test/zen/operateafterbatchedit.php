#!/usr/bin/env php
<?php

/**

title=测试 bugZen::operateAfterBatchEdit();
timeout=0
cid=0

- 步骤1:status从active变为resolved @1
- 步骤2:status保持active不变 @1
- 步骤3:status从closed变为resolved @1
- 步骤4:bug未设置status属性 @1
- 步骤5:status从resolved变为active @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('bug')->gen(10);
zenData('user')->gen(10);

su('admin');

$bugTest = new bugZenTest();

$bug1 = new stdClass();
$bug1->id = 1;
$bug1->status = 'resolved';
$bug1->resolvedBy = 'admin';

$oldBug1 = new stdClass();
$oldBug1->id = 1;
$oldBug1->status = 'active';
$oldBug1->feedback = 0;

$bug2 = new stdClass();
$bug2->id = 2;
$bug2->status = 'active';
$bug2->resolvedBy = '';

$oldBug2 = new stdClass();
$oldBug2->id = 2;
$oldBug2->status = 'active';
$oldBug2->feedback = 0;

$bug3 = new stdClass();
$bug3->id = 3;
$bug3->status = 'resolved';
$bug3->resolvedBy = 'admin';

$oldBug3 = new stdClass();
$oldBug3->id = 3;
$oldBug3->status = 'closed';
$oldBug3->feedback = 0;

$bug4 = new stdClass();
$bug4->id = 4;
$bug4->resolvedBy = 'admin';

$oldBug4 = new stdClass();
$oldBug4->id = 4;
$oldBug4->status = 'active';
$oldBug4->feedback = 0;

$bug5 = new stdClass();
$bug5->id = 5;
$bug5->status = 'active';
$bug5->resolvedBy = '';

$oldBug5 = new stdClass();
$oldBug5->id = 5;
$oldBug5->status = 'resolved';
$oldBug5->feedback = 0;

r($bugTest->operateAfterBatchEditTest($bug1, $oldBug1)) && p() && e('1'); // 步骤1:status从active变为resolved
r($bugTest->operateAfterBatchEditTest($bug2, $oldBug2)) && p() && e('1'); // 步骤2:status保持active不变
r($bugTest->operateAfterBatchEditTest($bug3, $oldBug3)) && p() && e('1'); // 步骤3:status从closed变为resolved
r($bugTest->operateAfterBatchEditTest($bug4, $oldBug4)) && p() && e('1'); // 步骤4:bug未设置status属性
r($bugTest->operateAfterBatchEditTest($bug5, $oldBug5)) && p() && e('1'); // 步骤5:status从resolved变为active