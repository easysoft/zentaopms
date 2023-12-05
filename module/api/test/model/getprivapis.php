#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('doclib')->config('doclib')->gen(10);
zdTable('api')->gen(10);

/**

title=测试 apiModel->getPrivApis();
timeout=0
cid=1

- 测试获取空发布的文档列表。
 - 第1条的id属性 @1
 - 第2条的id属性 @2

*/

global $tester;
$tester->loadModel('api');

r($tester->api->getPrivApis()) && p('1:id;2:id') && e('1,2'); // 测试有权限的文档库接口文档列表。
