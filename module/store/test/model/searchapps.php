#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 storeModel->searchAppsTest().
cid=1

- 测试不传参使用默认值获取 @Success
- 测试按照最近更新获取 @Success
- 测试按照更新上架获取 @Success
- 测试通过关键字获取 @Success
- 测试获取第二页应用 @Success
- 测试获取第一页每页5条应用 @5
- 测试获取第一页每页10条应用 @10

*/


include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/store.class.php';

zdTable('user')->gen(5);
su('admin');

$orderBy    = array('', 'update_time', 'create_time');
$keyword    = array('', '禅道');
$categories = array();
$page       = array(1, 2);
$pageSize   = array(5, 10);

$store = new storeTest();
r($store->searchAppsTest()) && p() && e('Success'); //测试不传参使用默认值获取

r($store->searchAppsTest($orderBy[1])) && p() && e('Success'); //测试按照最近更新获取
r($store->searchAppsTest($orderBy[2])) && p() && e('Success'); //测试按照更新上架获取

r($store->searchAppsTest($orderBy[0], $keyword[1])) && p() && e('Success'); //测试通过关键字获取

r($store->searchAppsTest($orderBy[0], $keyword[0], $categories, $page[1])) && p() && e('Success'); //测试获取第二页应用

r($store->searchAppsTest($orderBy[0], $keyword[0], $categories, $page[0], $pageSize[0])) && p() && e('5');  //测试获取第一页每页5条应用
r($store->searchAppsTest($orderBy[0], $keyword[0], $categories, $page[0], $pageSize[1])) && p() && e('10'); //测试获取第一页每页10条应用
