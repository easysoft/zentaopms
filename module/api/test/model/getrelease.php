#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('api_lib_release')->gen(10);

/**

title=测试 apiModel->getRelease();
timeout=0
cid=1

- 测试获取文档库ID为1，发布ID为1的发布信息。
 - 属性id @1
 - 属性lib @1
 - 属性version @version1.0
- 测试获取文档库ID为5，发布ID为5的发布信息。
 - 属性id @5
 - 属性lib @5
 - 属性version @version1.4
- 测试获取文档库ID为1，发布ID为2的发布信息。
 - 属性id @0
 - 属性lib @0
 - 属性version @0
- 测试获取文档库ID为1，发布版本号为version1.0的发布信息。
 - 属性id @1
 - 属性lib @1
 - 属性version @version1.0

*/

global $tester;
$tester->loadModel('api');
r($tester->api->getRelease(1, 'byID', 1))                 && p('id,lib,version') && e('1,1,version1.0'); // 测试获取文档库ID为1，发布ID为1的发布信息。
r($tester->api->getRelease(5, 'byID', 5))                 && p('id,lib,version') && e('5,5,version1.4'); // 测试获取文档库ID为5，发布ID为5的发布信息。
r($tester->api->getRelease(1, 'byID', 2))                 && p('id,lib,version') && e('0,0,0');          // 测试获取文档库ID为1，发布ID为2的发布信息。
r($tester->api->getRelease(1, 'byVersion', 'version1.0')) && p('id,lib,version') && e('1,1,version1.0'); // 测试获取文档库ID为1，发布版本号为version1.0的发布信息。
