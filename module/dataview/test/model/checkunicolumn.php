#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('product')->gen(10);

/**

title=测试 dataviewModel::checkUniColumn();
timeout=0
cid=1

- 检查产品表是否有重复数据。 @1

*/
global $tester;
$tester->loadModel('dataview');

r($tester->dataview->checkUniColumn('select * from zt_product')) && p() && e('1');  //检查产品表是否有重复数据。
