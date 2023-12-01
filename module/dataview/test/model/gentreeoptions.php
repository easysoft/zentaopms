#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 dataviewModel::genTreeOptions();
timeout=0
cid=1

- 获取treeOptions。 @0

*/
global $tester;
$tester->loadModel('dataview');

$dataview = new stdclass();

r($tester->dataview->genTreeOptions($dataview, array('aa' => '1', 'bb' => '2'), array('aa', 'bb'))) && p() && e('0');  //获取treeOptions。
