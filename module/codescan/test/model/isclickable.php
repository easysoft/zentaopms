#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 codescanModel->isClickable();
timeout=0
cid=0

- 测试enable操作（状态为disabled） >> 1
- 测试disable操作（状态为enabled） >> 1
- 测试enable操作（状态已enabled） >> 0
- 测试disable操作（状态已disabled） >> 0
- 测试bug操作（无bugID且状态未关闭） >> 1

*/

su('admin');
$test = new codescanModelTest();

$cs1 = new stdclass(); $cs1->status = 'disabled';
r($test->isClickableTest($cs1, 'enable')) && p() && e('1');

$cs2 = new stdclass(); $cs2->status = 'enabled';
r($test->isClickableTest($cs2, 'disable')) && p() && e('1');

$cs3 = new stdclass(); $cs3->status = 'enabled';
r($test->isClickableTest($cs3, 'enable')) && p() && e('0');

$cs4 = new stdclass(); $cs4->status = 'disabled';
r($test->isClickableTest($cs4, 'disable')) && p() && e('0');

$cs5 = new stdclass(); $cs5->bugID = 0; $cs5->status = 'active';
r($test->isClickableTest($cs5, 'bug')) && p() && e('1');
