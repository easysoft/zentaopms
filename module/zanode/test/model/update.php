#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 chartModel->update().
timeout=0
cid=1

- 更新一个测试节点，检查更新的name，extranet，desc字段是否正确。
 - 第0条的field属性 @name
 - 第0条的old属性 @~~
 - 第0条的new属性 @test name
 - 第1条的field属性 @extranet
 - 第1条的old属性 @10.0.0.1
 - 第1条的new属性 @test extranet
 - 第5条的field属性 @desc
 - 第5条的old属性 @~~
 - 第5条的new属性 @test desc
- 更新一个测试节点，检查更新的memory，diskSize，osName字段是否正确。
 - 第2条的field属性 @memory
 - 第2条的old属性 @~~
 - 第2条的new属性 @3.13
 - 第3条的field属性 @diskSize
 - 第3条的old属性 @~~
 - 第3条的new属性 @3.14
 - 第4条的field属性 @osName
 - 第4条的old属性 @linux
 - 第4条的new属性 @Linux
- 设置name字段为必填字段，但是不填写name字段，执行更新操作以后返回false,数据并未更新。 @0
- 查看name字段的错误信息。name字段的第一条属性 @『名称』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

$zanode = new zanodeTest();

zdTable('host')->config('host')->gen(1);

$hostInfo = new stdclass();

$hostInfo->name     = 'test name';
$hostInfo->extranet = 'test extranet';
$hostInfo->memory = 3.13;
$hostInfo->diskSize = 3.14;
$hostInfo->osName = 'Linux';
$hostInfo->desc = 'test desc';

$hostID = 1;
$diff = $zanode->update($hostID, $hostInfo);
r($diff) && p('0:field,old,new;1:field,old,new;5:field,old,new') && e('name,~~,test name;extranet,10.0.0.1,test extranet;desc,~~,test desc');   //更新一个测试节点，检查更新的name，extranet，desc字段是否正确。
r($diff) && p('2:field,old,new;3:field,old,new;4:field,old,new') && e('memory,~~,3.13;diskSize,~~,3.14;osName,linux,Linux');                    //更新一个测试节点，检查更新的memory，diskSize，osName字段是否正确。

zdTable('host')->config('host')->gen(1);
global $config;
$config->zanode->edit->requiredFields = 'name';
$hostInfo->name = '';
r($zanode->update($hostID, $hostInfo)) && p('') && e('0');     //设置name字段为必填字段，但是不填写name字段，执行更新操作以后返回false,数据并未更新。
r(dao::getError()) && p('name:0') && e('『名称』不能为空。');  //查看name字段的错误信息。
