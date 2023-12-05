#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('api')->gen(50);
zdTable('apispec')->gen(100);

/**

title=测试 apiModel->getApiListBySearch();
timeout=0
cid=1

- 测试获取文档库ID为1的文档列表。
 - 第0条的id属性 @1
 - 第0条的title属性 @BUG接口1
 - 第0条的path属性 @bug-getList
 - 第0条的status属性 @doing
- 测试获取文档库ID为2的文档列表。
 - 第0条的id属性 @2
 - 第0条的title属性 @BUG接口2
 - 第0条的path属性 @bug-getList
 - 第0条的status属性 @done

*/

global $tester;
$tester->loadModel('api');

r($tester->api->getApiListBySearch(1,1)) && p('0:id,title,path,status') && e('1,BUG接口1,bug-getList,doing'); // 测试获取文档库ID为1的文档列表。
r($tester->api->getApiListBySearch(2,1)) && p('0:id,title,path,status') && e('2,BUG接口2,bug-getList,done');  // 测试获取文档库ID为2的文档列表。
