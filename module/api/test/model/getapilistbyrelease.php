#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('api')->gen(50);
zdTable('apispec')->gen(100);

/**

title=测试 apiModel->getApiListByRelease();
timeout=0
cid=1

- 测试获取空发布的文档列表。
 - 第0条的id属性 @1
 - 第0条的title属性 @BUG接口1
 - 第0条的path属性 @bug-getList
 - 第0条的status属性 @doing
 - 第10条的id属性 @2
 - 第10条的title属性 @BUG接口2
 - 第10条的path属性 @bug-getList
 - 第10条的status属性 @done
- 测试获取指定发布的文档列表。
 - 第1条的id属性 @1
 - 第1条的title属性 @BUG接口1
 - 第1条的path属性 @bug-getList
 - 第1条的status属性 @done
 - 第3条的id属性 @2
 - 第3条的title属性 @BUG接口2
 - 第3条的path属性 @bug-getList
 - 第3条的status属性 @hidden
- 测试获取指定发布的文档列表并且按照指定where条件查询。
 - 第0条的id属性 @2
 - 第0条的title属性 @BUG接口2
 - 第0条的path属性 @bug-getList
 - 第0条的status属性 @done

*/

global $tester;
$tester->loadModel('api');

$release = new stdclass();
r($tester->api->getApiListByRelease($release)) && p('0:id,title,path,status;10:id,title,path,status') && e('1,BUG接口1,bug-getList,doing,2,BUG接口2,bug-getList,done'); // 测试获取空发布的文档列表。

$release->snap['apis'][] = array('id' => 1, 'version' => 1);
$release->snap['apis'][] = array('id' => 1, 'version' => 2);
$release->snap['apis'][] = array('id' => 2, 'version' => 1);
$release->snap['apis'][] = array('id' => 2, 'version' => 2);

r($tester->api->getApiListByRelease($release)) && p('1:id,title,path,status;3:id,title,path,status') && e('1,BUG接口1,bug-getList,done,2,BUG接口2,bug-getList,hidden'); // 测试获取指定发布的文档列表。

r($tester->api->getApiListByRelease($release, ' spec.id = 11 ')) && p('0:id,title,path,status') && e('2,BUG接口2,bug-getList,done'); // 测试获取指定发布的文档列表并且按照指定where条件查询。
