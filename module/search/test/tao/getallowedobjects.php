#!/usr/bin/env php
<?php

/**

title=测试 searchTao::getAllowedObjects();
timeout=0
cid=0

- 步骤1：传入具体对象数组时保持原顺序返回首项 @task
- 步骤2：传入具体对象数组时会保留 feedback @feedback
- 步骤3：统计全部对象时包含 task @1
- 步骤4：非轻量模式下会包含 program @1
- 步骤5：轻量模式下会移除 program @0
- 步骤6：全部对象列表中包含 deploystep @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$search = new searchTaoTest();

$specificObjects = $search->getAllowedObjectsTest(array('task', 'bug', 'feedback'));
$allObjects      = $search->getAllowedObjectsTest('all');

$oldSystemMode      = $config->systemMode;
$config->systemMode = 'light';
$lightObjects       = $search->getAllowedObjectsTest('all');
$config->systemMode = $oldSystemMode;

r($specificObjects[0])                       && p() && e('task');     // 步骤1：传入具体对象数组时保持原顺序返回首项
r($specificObjects[2])                       && p() && e('feedback'); // 步骤2：传入具体对象数组时会保留 feedback
r(in_array('task', $allObjects))             && p() && e('1');        // 步骤3：统计全部对象时包含 task
r(in_array('program', $allObjects))          && p() && e('1');        // 步骤4：非轻量模式下会包含 program
r(in_array('program', $lightObjects))        && p() && e('0');        // 步骤5：轻量模式下会移除 program
r(in_array('deploystep', $allObjects))       && p() && e('1');        // 步骤6：全部对象列表中包含 deploystep
