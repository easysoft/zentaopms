#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('apistruct')->gen(10);

/**

title=测试 apiModel->getStructByQuery();
timeout=0
cid=1

- 获取文档目录ID为1的数据结构列表。
 - 第0条的id属性 @1
 - 第0条的name属性 @数据接口1
 - 第0条的type属性 @formData
- 获取文档目录ID为2的数据结构列表。
 - 第0条的id属性 @2
 - 第0条的name属性 @数据接口2
 - 第0条的type属性 @json

*/

global $tester;
$tester->loadModel('api');

r($tester->api->getStructByQuery(1)) && p('0:id,name,type') && e('1,数据接口1,formData'); //获取文档目录ID为1的数据结构列表。
r($tester->api->getStructByQuery(2)) && p('0:id,name,type') && e('2,数据接口2,json');     //获取文档目录ID为2的数据结构列表。
