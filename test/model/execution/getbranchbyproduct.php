#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=executionModel->getBranchByProduct();
cid=1
pid=1

正常产品分支统计 >> 0
分支产品分支查看 >> 分支2
分支产品分支统计 >> 1

*/

$productIDList = array('1', '41');
$count         = array('0', '1');

$execution = new executionTest();
r($execution->getBranchByProductTest($productIDList[0],$count[1])) && p() && e('0');           //正常产品分支统计
r($execution->getBranchByProductTest($productIDList[1],$count[0])) && p('41:2') && e('分支2'); //分支产品分支查看
r($execution->getBranchByProductTest($productIDList[1],$count[1])) && p() && e('1');           //分支产品分支统计