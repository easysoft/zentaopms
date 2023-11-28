#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 dataviewModel::replace4Workflow();
timeout=0
cid=1

- 替换产品名称的产品为自定义语言项。 @产品名称

*/
global $tester;
$tester->loadModel('dataview');

r($tester->dataview->replace4Workflow('产品名称')) && p() && e('产品名称');  //替换产品名称的产品为自定义语言项。
