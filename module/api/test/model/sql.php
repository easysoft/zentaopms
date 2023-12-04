#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('api')->gen(10);

/**

title=测试 apiModel->sql();
timeout=0
cid=1

- 在没启用配置的时候调用sql接口。 @因为安全原因，该功能被禁用。可以到config目录，修改配置项 $config->features->apiSQL，打开此功能。
- SQL语句为空时调用sql接口。
 - 属性status @fail
 - 属性message @` `
- SQL语句不符合规范时调用sql接口。
 - 属性status @fail
 - 属性message @SQL查询接口只允许SELECT查询
- 使用正确的SQL查询调用sql接口。
 - 第0条的id属性 @1
 - 第0条的title属性 @BUG接口1
 - 第1条的id属性 @2
 - 第1条的title属性 @BUG接口2
- 使用正确的SQL查询并按照以id作为键返回sql接口的查询结果。
 - 第1条的id属性 @1
 - 第1条的title属性 @BUG接口1
 - 第2条的id属性 @2
 - 第2条的title属性 @BUG接口2

*/

global $tester, $config;
$tester->loadModel('api');

$sql = '';
$config->features->apiSQL = false;
r($tester->api->sql($sql)) && p('status,message') && e('fail,因为安全原因，该功能被禁用。可以到config目录，修改配置项 $config->features->apiSQL，打开此功能。'); //在没启用配置的时候调用sql接口。

$config->features->apiSQL = true;
r($tester->api->sql($sql)) && p('status,message') && e('fail,` `');                         //SQL语句为空时调用sql接口。

$sql = 'delete from zt_api';
r($tester->api->sql($sql)) && p('status,message') && e('fail,SQL查询接口只允许SELECT查询'); //SQL语句不符合规范时调用sql接口。

$sql = 'select * from zt_api';
$result = $tester->api->sql($sql);
r($result['data']) && p('0:id,title;1:id,title') && e('1,BUG接口1,2,BUG接口2');             //使用正确的SQL查询调用sql接口。

$result = $tester->api->sql($sql, 'id');
r($result['data']) && p('1:id,title;2:id,title') && e('1,BUG接口1,2,BUG接口2');             //使用正确的SQL查询并按照以id作为键返回sql接口的查询结果。
