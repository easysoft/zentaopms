#!/usr/bin/env php
<?php
/**

title=测试 pipelineModel->getUserBindedPairs();
cid=1

- 获取Id为0、type为空的用户绑定信息 @0
- 获取Id为0、type为gitlab的用户绑定信息 @0
- 获取Id为0、type为test的用户绑定信息 @0
- 获取Id为1、type为空的用户绑定信息 @0
- 获取Id为1、type为gitlab的用户绑定信息属性1 @root
- 获取Id为1、type为gitlab的用户绑定信息属性root @1
- 获取Id为1、type为test的用户绑定信息 @0
- 获取Id为2、type为空的用户绑定信息 @0
- 获取Id为2、type为gitlab的用户绑定信息 @0
- 获取Id为2、type为test的用户绑定信息 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pipeline.class.php';

zdTable('user')->gen(5);
zdTable('oauth')->config('oauth')->gen(1);

$providerIds   = array(0, 1, 2);
$providerTypes = array('', 'gitlab', 'test');
$fieldList     = array('openID,account', 'account,openID');

$pipelineTester = new pipelineTest();
r($pipelineTester->getUserBindedPairsTest($providerIds[0], $providerTypes[0], $fieldList[0])) && p()       && e('0');    // 获取Id为0、type为空的用户绑定信息
r($pipelineTester->getUserBindedPairsTest($providerIds[0], $providerTypes[1], $fieldList[1])) && p()       && e('0');    // 获取Id为0、type为gitlab的用户绑定信息
r($pipelineTester->getUserBindedPairsTest($providerIds[0], $providerTypes[2], $fieldList[0])) && p()       && e('0');    // 获取Id为0、type为test的用户绑定信息
r($pipelineTester->getUserBindedPairsTest($providerIds[1], $providerTypes[0], $fieldList[0])) && p()       && e('0');    // 获取Id为1、type为空的用户绑定信息
r($pipelineTester->getUserBindedPairsTest($providerIds[1], $providerTypes[1], $fieldList[0])) && p('1')    && e('root'); // 获取Id为1、type为gitlab的用户绑定信息
r($pipelineTester->getUserBindedPairsTest($providerIds[1], $providerTypes[1], $fieldList[1])) && p('root') && e('1');    // 获取Id为1、type为gitlab的用户绑定信息
r($pipelineTester->getUserBindedPairsTest($providerIds[1], $providerTypes[2], $fieldList[0])) && p()       && e('0');    // 获取Id为1、type为test的用户绑定信息
r($pipelineTester->getUserBindedPairsTest($providerIds[2], $providerTypes[0], $fieldList[0])) && p()       && e('0');    // 获取Id为2、type为空的用户绑定信息
r($pipelineTester->getUserBindedPairsTest($providerIds[2], $providerTypes[1], $fieldList[0])) && p()       && e('0');    // 获取Id为2、type为gitlab的用户绑定信息
r($pipelineTester->getUserBindedPairsTest($providerIds[2], $providerTypes[2], $fieldList[0])) && p()       && e('0');    // 获取Id为2、type为test的用户绑定信息
