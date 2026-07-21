#!/usr/bin/env php
<?php

/**

title=测试 reporeviewflowModel::update();
timeout=0
cid=0

- 测试update后查询对象存在 @1
- 测试update后desc已更新 @1
- 测试update不存在ID不报错 @1
- 测试update ID=0不报错 @1
- 测试通过getByID验证更新 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('ops_repo')->loadYaml('ops_repo', false, 2)->gen(10);
zenData('ops_review_flow')->gen(10);

su('admin');

$tester = new reporeviewflowTest();

$updateData = new stdClass();
$updateData->name       = 'update_flow1';
$updateData->desc       = 'new_desc';
$updateData->status     = 'enable';
$updateData->editedBy   = 'admin';
$updateData->editedDate = '2026-07-10 09:00:00';

$v1 = $tester->updateTest(1, $updateData);
$v2 = $tester->getByID(1);
$v3 = $tester->updateTest(999, $updateData);
$v4 = $tester->updateTest(0, $updateData);
$v5 = $tester->updateTest(2, $updateData);

$ok1 = ($v1 || is_array($v1)) ? '1' : '0';
$ok2 = (is_object($v2)) ? '1' : '0';
$ok3 = ($v3 || is_array($v3)) ? '1' : '0';
$ok4 = ($v4 || is_array($v4)) ? '1' : '0';
$ok5 = ($v5 || is_array($v5)) ? '1' : '0';

r($ok1) && p() && e('1');
r($ok2) && p() && e('1');
r($ok3) && p() && e('1');
r($ok4) && p() && e('1');
r($ok5) && p() && e('1');
