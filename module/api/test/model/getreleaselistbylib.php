#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('api_lib_release')->gen(10);

/**

title=测试 apiModel->getReleaseListByLib();
timeout=0
cid=1

- 测试获取文档库ID为1的发布信息。
 - 第1条的id属性 @1
 - 第1条的lib属性 @1
 - 第1条的version属性 @version1.0
- 测试获取文档库ID为2的发布信息。
 - 第2条的id属性 @2
 - 第2条的lib属性 @2
 - 第2条的version属性 @version1.1
- 测试获取文档库ID为22的发布信息。
 - 第22条的id属性 @0
 - 第22条的lib属性 @0
 - 第22条的version属性 @0

*/

global $tester;
$tester->loadModel('api');
r($tester->api->getReleaseListByLib(1))  && p('1:id,lib,version')  && e('1,1,version1.0'); // 测试获取文档库ID为1的发布信息。
r($tester->api->getReleaseListByLib(2))  && p('2:id,lib,version')  && e('2,2,version1.1'); // 测试获取文档库ID为2的发布信息。
r($tester->api->getReleaseListByLib(22)) && p('22:id,lib,version') && e('0,0,0');          // 测试获取文档库ID为22的发布信息。
