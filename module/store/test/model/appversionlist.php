#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 storeModel->appVersionList().
cid=1

- 测试ID为0 @0
- 测试获取禅道开源版的version第2023.12.2801条的version属性 @2023.12.2801
- 测试传参name为zentao第2023.12.2801条的version属性 @2023.12.2801
- 测试传参channel为stable第2023.12.2801条的version属性 @2023.12.2801
- 测试获取第二页信息第2023.9.1201条的version属性 @2023.9.1201
- 测试获取第二页信息 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/store.class.php';

zdTable('user')->gen(5);
su('admin');

$appIdList    = array(0, 29);
$nameList     = array('', 'zentao');
$channelList  = array('', 'stable');
$pageList     = array(1, 2);
$pageSizeList = array(3, 5);

$store = new storeTest();
r($store->appVersionListTest($appIdList[0], $nameList[0], $channelList[0], $pageList[0], $pageSizeList[0])) && p() && e('0'); //测试ID为0
r($store->appVersionListTest($appIdList[1], $nameList[0], $channelList[0], $pageList[0], $pageSizeList[0])) && p('2023.12.2801:version') && e('2023.12.2801'); //测试获取禅道开源版的version
r($store->appVersionListTest($appIdList[1], $nameList[1], $channelList[0], $pageList[0], $pageSizeList[0])) && p('2023.12.2801:version') && e('2023.12.2801'); //测试传参name为zentao
r($store->appVersionListTest($appIdList[1], $nameList[0], $channelList[1], $pageList[0], $pageSizeList[0])) && p('2023.12.2801:version') && e('2023.12.2801'); //测试传参channel为stable
r($store->appVersionListTest($appIdList[1], $nameList[0], $channelList[0], $pageList[1], $pageSizeList[0])) && p('2023.9.1201:version')  && e('2023.9.1201');  //测试获取第二页信息
r($store->appVersionListTest($appIdList[1], $nameList[0], $channelList[0], $pageList[0], $pageSizeList[1])) && p() && e('5'); //测试获取第二页信息
