#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 storeModel->appDynamic().
timeout=0
cid=18448

- 测试获取应用动态第一页信息 @Success
- 测试每页展示5条动态 @5
- 测试每页最多展示10条动态 @7
- 测试每页最多展示15条动态 @7
- 测试每页最多展示0条动态 @7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

$pageIdList = array(1, 2);
$pageList   = array(20, 5, 10, 15, 30);

$store = new storeModelTest();
r($store->appDynamicTest(29, $pageIdList[0], $pageList[0])) && p() && e('Success'); //测试获取应用动态第一页信息
r($store->appDynamicTest(29, $pageIdList[0], $pageList[1])) && p() && e('5');       //测试每页展示5条动态
r($store->appDynamicTest(29, $pageIdList[0], $pageList[2])) && p() && e('7');       //测试每页最多展示10条动态
r($store->appDynamicTest(29, $pageIdList[0], $pageList[3])) && p() && e('7');       //测试每页最多展示15条动态
r($store->appDynamicTest(29, $pageIdList[0], 0))            && p() && e('7');       //测试每页最多展示0条动态
