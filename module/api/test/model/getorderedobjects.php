#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('doclib')->config('doclib')->gen(300);
zdTable('project')->gen(100);
zdTable('product')->gen(100);

/**

title=测试 apiModel->getOrderedObjects();
timeout=0
cid=1

- 测试获取正常的产品和项目列表。
 - 第product条的1属性 @正常产品1
 - 第product条的2属性 @正常产品2
 - 第product条的44属性 @多分支产品44
 - 第project条的3属性 @项目集3
 - 第project条的4属性 @项目集4
 - 第project条的45属性 @项目45
- 测试获取已关闭的产品和项目列表。
 - 第product条的21属性 @已关闭的正常产品21
 - 第product条的22属性 @已关闭的正常产品22
 - 第product条的66属性 @已关闭的多分支产品66
 - 第project条的8属性 @项目集8
 - 第project条的16属性 @项目16
 - 第project条的96属性 @项目96

*/

global $tester;
$tester->loadModel('api');
$result = $tester->api->getOrderedObjects();
$normalObjects = $result[0];
$closedObjects = $result[1];

r($normalObjects) && p('product:1,2,44;project:3,4,45')    && e('正常产品1,正常产品2,多分支产品44,项目集3,项目集4,项目45');                          // 测试获取正常的产品和项目列表。
r($closedObjects) && p('product:21,22,66;project:8,16,96') && e('已关闭的正常产品21,已关闭的正常产品22,已关闭的多分支产品66,项目集8,项目16,项目96'); // 测试获取已关闭的产品和项目列表。
