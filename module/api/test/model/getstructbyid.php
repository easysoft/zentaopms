#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('apistruct')->gen(10);

/**

title=测试 apiModel->getStructByID();
timeout=0
cid=15115

- 获取数据结构ID为1的数据结构。
 - 属性id @1
 - 属性name @数据接口1
 - 属性type @formData
- 获取数据结构ID为2的数据结构。
 - 属性id @2
 - 属性name @数据接口2
 - 属性type @json
- 获取不存在的数据结构ID为22的数据结构。
 - 属性id @0
 - 属性name @0
 - 属性type @0

*/

global $tester;
$tester->loadModel('api');

r($tester->api->getStructByID(1))  && p('id,name,type') && e('1,数据接口1,formData'); //获取数据结构ID为1的数据结构。
r($tester->api->getStructByID(2))  && p('id,name,type') && e('2,数据接口2,json');     //获取数据结构ID为2的数据结构。
r($tester->api->getStructByID(22)) && p('id,name,type') && e('0,0,0');                //获取不存在的数据结构ID为22的数据结构。
