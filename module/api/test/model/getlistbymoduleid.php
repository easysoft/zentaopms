#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('module')->config('module')->gen(10);
zdTable('api')->config('api')->gen(50);
zdTable('apispec')->gen(100);
zdTable('api_lib_release')->gen(10);

/**

title=测试 apiModel->getListByModuleID();
timeout=0
cid=1

- 测试不传参数时获取的文档列表。 @0
- 测试获取文档库ID为1的文档列表。
 - 第0条的id属性 @1
 - 第0条的title属性 @BUG接口1
 - 第0条的path属性 @bug-getList
 - 第0条的status属性 @doing
- 测试获取文档库ID为1并且模块ID为1的文档列表。
 - 第0条的id属性 @1
 - 第0条的title属性 @BUG接口1
 - 第0条的path属性 @bug-getList
 - 第0条的status属性 @doing
- 测试获取文档库ID为1并且发布ID为1的文档列表。
 - 第0条的id属性 @1
 - 第0条的title属性 @BUG接口1
 - 第0条的path属性 @bug-getList
 - 第0条的status属性 @doing

*/

global $tester;
$tester->loadModel('api');

$release = new stdclass();
r($tester->api->getListByModuleID()) && p() && e('0');                                                           // 测试不传参数时获取的文档列表。
r($tester->api->getListByModuleID(1)) && p('0:id,title,path,status') && e('1,BUG接口1,bug-getList,doing');       // 测试获取文档库ID为1的文档列表。
r($tester->api->getListByModuleID(1, 1)) && p('0:id,title,path,status') && e('1,BUG接口1,bug-getList,doing');    // 测试获取文档库ID为1并且模块ID为1的文档列表。
r($tester->api->getListByModuleID(1, 1, 1)) && p('0:id,title,path,status') && e('1,BUG接口1,bug-getList,doing'); // 测试获取文档库ID为1并且发布ID为1的文档列表。
