#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('branch')->config('branch')->gen(30);
zdTable('user')->gen(5);
su('admin');

/**

title=测试 branchModel->getStatusList();
timeout=0
cid=1

*/

$productIdList = array(1, 6, 11);

global $tester;
$tester->loadModel('branch');
r($tester->branch->getStatusList($productIdList[0])) && p()    && e('0');      // 获取正常产品的分支状态列表
r($tester->branch->getStatusList($productIdList[1])) && p('1') && e('active'); // 获取多分支产品的分支状态列表
r($tester->branch->getStatusList($productIdList[2])) && p()    && e('0');      // 获取不存在产品的分支状态列表
