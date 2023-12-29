#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 storeModel->appDynamic().
cid=1

- 测试获取应用动态第一页信息 @Success
- 测试每页展示5条动态 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/store.class.php';

zdTable('user')->gen(5);
su('admin');

$pageIdList = array(1, 2);
$pageList   = array(20, 5);

$store = new storeTest();
r($store->appDynamicTest(29, $pageIdList[0], $pageList[0])) && p() && e('Success'); //测试获取应用动态第一页信息
r($store->appDynamicTest(29, $pageIdList[0], $pageList[1])) && p() && e('5');       //测试每页展示5条动态
