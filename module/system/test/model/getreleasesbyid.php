#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
zenData('system')->gen(10);
zenData('product')->gen(10);
zenData('release')->gen(10);

/**

title=测试 systemModel::getList();
timeout=0
cid=18740

- 查询应用id为1的应用关联的发布名称第1条的name属性 @产品正常的正常的发布1
- 查询应用id为1的应用关联的发布数量 @1
- 查询应用id为0的应用关联的发布 @0
- 查询应用id为2的应用关联的发布第2条的id属性 @2
- 查询应用id为3的应用关联的发布名称第3条的name属性 @产品正常的正常的发布3
*/
global $tester;
$system = $tester->loadModel('system');

r($system->getReleasesByID(1))        && p('1:name') && e('产品正常的正常的发布1'); // 查询应用id为1的应用关联的发布名称
r(count($system->getReleasesByID(1))) && p()         && e('1');                     // 查询应用id为1的应用关联的发布数量
r($system->getReleasesByID(0))        && p()         && e('0');                     // 查询应用id为0的应用关联的发布

r($system->getReleasesByID(2)) && p('2:id')   && e('2');                     // 查询应用id为2的应用关联的发布
r($system->getReleasesByID(3)) && p('3:name') && e('产品正常的正常的发布3'); // 查询应用id为3的应用关联的发布名称
