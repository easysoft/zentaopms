#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('api_lib_release')->gen(110);

/**

title=测试 apiModel->getReleaseByQuery();
timeout=0
cid=1

- 测试获取文档库ID为1的发布列表。
 - 第0条的id属性 @1
 - 第0条的lib属性 @1
 - 第0条的version属性 @version1.0
 - 第1条的id属性 @101
 - 第1条的lib属性 @1
 - 第1条的version属性 @version5.0
- 测试获取文档库ID为1，排序条件为ID倒序的发布列表。
 - 第0条的id属性 @101
 - 第0条的lib属性 @1
 - 第0条的version属性 @version5.0
 - 第1条的id属性 @1
 - 第1条的lib属性 @1
 - 第1条的version属性 @version1.0
- 测试获取文档库ID不存在的的发布列表。
 - 第0条的id属性 @0
 - 第0条的lib属性 @0
 - 第0条的version属性 @0

*/

global $tester;
$tester->loadModel('api');
r($tester->api->getReleaseByQuery(1))                  && p('0:id,lib,version;1:id,lib,version') && e('1,1,version1.0,101,1,version5.0'); // 测试获取文档库ID为1的发布列表。
r($tester->api->getReleaseByQuery(1, null, 'id_desc')) && p('0:id,lib,version;1:id,lib,version') && e('101,1,version5.0,1,1,version1.0'); // 测试获取文档库ID为1，排序条件为ID倒序的发布列表。
r($tester->api->getReleaseByQuery(999))                && p('0:id,lib,version') && e('0,0,0'); // 测试获取文档库ID不存在的的发布列表。
