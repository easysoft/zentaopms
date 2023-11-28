#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 dataviewModel::isClickable();
timeout=0
cid=1

- 获取create操作的可点击验证。 @1
- 获取edit操作的可点击验证。 @1

*/
global $tester;
$tester->loadModel('dataview');

$dataview = new stdclass();

r($tester->dataview->isClickable($dataview, 'create')) && p() && e('1');  //获取create操作的可点击验证。
r($tester->dataview->isClickable($dataview, 'edit'))   && p() && e('1');  //获取edit操作的可点击验证。
