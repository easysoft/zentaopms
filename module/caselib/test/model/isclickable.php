#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 caselibModel->isClickable();
cid=1

- 测试操作为空时，是否可点击 @1
- 测试操作为编辑时，是否可点击 @1
- 测试操作为删除时，是否可点击 @1
- 测试操作为不存在时，是否可点击 @1

*/

su('admin');

$lib     = new stdclass();
$actions = array('', 'edit', 'delete', 'test');

global $tester;
$caselibModel = $tester->loadModel('caselib');
r($caselibModel->isClickable($lib, $actions[0])) && p() && e('1'); // 测试操作为空时，是否可点击
r($caselibModel->isClickable($lib, $actions[1])) && p() && e('1'); // 测试操作为编辑时，是否可点击
r($caselibModel->isClickable($lib, $actions[2])) && p() && e('1'); // 测试操作为删除时，是否可点击
r($caselibModel->isClickable($lib, $actions[3])) && p() && e('1'); // 测试操作为不存在时，是否可点击
