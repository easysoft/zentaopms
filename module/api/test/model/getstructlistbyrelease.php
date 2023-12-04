#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('apistruct')->gen(10);
zdTable('apistruct_spec')->gen(10);

/**

title=测试 apiModel->getStructListByRelease();
timeout=0
cid=1

- 获取空发布下的数据结构列表。
 - 第0条的id属性 @1
 - 第0条的lib属性 @1
 - 第0条的name属性 @数据接口1
 - 第0条的type属性 @formData
 - 第0条的version属性 @1
 - 第1条的id属性 @2
 - 第1条的lib属性 @2
 - 第1条的name属性 @数据接口2
 - 第1条的type属性 @json
 - 第1条的version属性 @2
- 获取指定发布下的数据结构列表。
 - 第0条的id属性 @1
 - 第0条的lib属性 @1
 - 第0条的name属性 @数据接口1
 - 第0条的type属性 @formData
 - 第0条的version属性 @1
 - 第1条的id属性 @3
 - 第1条的lib属性 @3
 - 第1条的name属性 @数据接口3
 - 第1条的type属性 @array
 - 第1条的version属性 @3
- 获取指定发布下的数据结构列表。
 - 第0条的id属性 @3
 - 第0条的lib属性 @3
 - 第0条的name属性 @数据接口3
 - 第0条的type属性 @array
 - 第0条的version属性 @3

*/

global $tester;
$tester->loadModel('api');

$release = new stdclass();
r($tester->api->getStructListByRelease($release)) && p('0:id,lib,name,type,version;1:id,lib,name,type,version') && e('1,1,数据接口1,formData,1,2,2,数据接口2,json,2'); //获取空发布下的数据结构列表。

$release->snap['structs'][] = array('id' => 1, 'version' => 1);
$release->snap['structs'][] = array('id' => 3, 'version' => 3);
r($tester->api->getStructListByRelease($release)) && p('0:id,lib,name,type,version;1:id,lib,name,type,version') && e('1,1,数据接口1,formData,1,3,3,数据接口3,array,3'); //获取指定发布下的数据结构列表。

r($tester->api->getStructListByRelease($release, 'object.id = 3 ')) && p('0:id,lib,name,type,version') && e('3,3,数据接口3,array,3'); //获取指定发布下并且带有查询条件的数据结构列表。
