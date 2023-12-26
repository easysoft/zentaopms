#!/usr/bin/env php
<?php
/**

title=测试 pipelineModel->getOpenIdByAccount();
cid=1

- 获取providerID为0、providerType为空、account为空的openID @0
- 获取providerID为0、providerType为空、account为admin的openID @0
- 获取providerID为0、providerType为空、account为test的openID @0
- 获取providerID为0、providerType为gitlab、account为空的openID @0
- 获取providerID为0、providerType为gitlab、account为admin的openID @0
- 获取providerID为0、providerType为gitlab、account为test的openID @0
- 获取providerID为0、providerType为test、account为空的openID @0
- 获取providerID为0、providerType为test、account为admin的openID @0
- 获取providerID为0、providerType为test、account为test的openID @0
- 获取providerID为1、providerType为空、account为空的openID @0
- 获取providerID为1、providerType为空、account为admin的openID @0
- 获取providerID为1、providerType为空、account为test的openID @0
- 获取providerID为1、providerType为gitlab、account为空的openID @0
- 获取providerID为1、providerType为gitlab、account为admin的openID @1
- 获取providerID为1、providerType为gitlab、account为test的openID @0
- 获取providerID为1、providerType为test、account为空的openID @0
- 获取providerID为1、providerType为test、account为admin的openID @0
- 获取providerID为1、providerType为test、account为test的openID @0
- 获取providerID为2、providerType为空、account为空的openID @0
- 获取providerID为2、providerType为空、account为admin的openID @0
- 获取providerID为2、providerType为空、account为test的openID @0
- 获取providerID为2、providerType为gitlab、account为空的openID @0
- 获取providerID为2、providerType为gitlab、account为admin的openID @0
- 获取providerID为2、providerType为gitlab、account为test的openID @0
- 获取providerID为2、providerType为test、account为空的openID @0
- 获取providerID为2、providerType为test、account为admin的openID @0
- 获取providerID为2、providerType为test、account为test的openID @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pipeline.class.php';

zdTable('user')->gen(5);
zdTable('oauth')->config('oauth')->gen(1);

$providerIds   = array(0, 1, 2);
$providerTypes = array('', 'gitlab', 'test');
$accounts      = array('', 'admin', 'test');

$pipelineTester = new pipelineTest();
r($pipelineTester->getOpenIdByAccountTest($providerIds[0], $providerTypes[0], $accounts[0])) && p() && e('0'); // 获取providerID为0、providerType为空、account为空的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[0], $providerTypes[0], $accounts[1])) && p() && e('0'); // 获取providerID为0、providerType为空、account为admin的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[0], $providerTypes[0], $accounts[2])) && p() && e('0'); // 获取providerID为0、providerType为空、account为test的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[0], $providerTypes[1], $accounts[0])) && p() && e('0'); // 获取providerID为0、providerType为gitlab、account为空的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[0], $providerTypes[1], $accounts[1])) && p() && e('0'); // 获取providerID为0、providerType为gitlab、account为admin的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[0], $providerTypes[1], $accounts[2])) && p() && e('0'); // 获取providerID为0、providerType为gitlab、account为test的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[0], $providerTypes[2], $accounts[0])) && p() && e('0'); // 获取providerID为0、providerType为test、account为空的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[0], $providerTypes[2], $accounts[1])) && p() && e('0'); // 获取providerID为0、providerType为test、account为admin的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[0], $providerTypes[2], $accounts[2])) && p() && e('0'); // 获取providerID为0、providerType为test、account为test的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[1], $providerTypes[0], $accounts[0])) && p() && e('0'); // 获取providerID为1、providerType为空、account为空的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[1], $providerTypes[0], $accounts[1])) && p() && e('0'); // 获取providerID为1、providerType为空、account为admin的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[1], $providerTypes[0], $accounts[2])) && p() && e('0'); // 获取providerID为1、providerType为空、account为test的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[1], $providerTypes[1], $accounts[0])) && p() && e('0'); // 获取providerID为1、providerType为gitlab、account为空的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[1], $providerTypes[1], $accounts[1])) && p() && e('1'); // 获取providerID为1、providerType为gitlab、account为admin的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[1], $providerTypes[1], $accounts[2])) && p() && e('0'); // 获取providerID为1、providerType为gitlab、account为test的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[1], $providerTypes[2], $accounts[0])) && p() && e('0'); // 获取providerID为1、providerType为test、account为空的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[1], $providerTypes[2], $accounts[1])) && p() && e('0'); // 获取providerID为1、providerType为test、account为admin的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[1], $providerTypes[2], $accounts[2])) && p() && e('0'); // 获取providerID为1、providerType为test、account为test的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[2], $providerTypes[0], $accounts[0])) && p() && e('0'); // 获取providerID为2、providerType为空、account为空的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[2], $providerTypes[0], $accounts[1])) && p() && e('0'); // 获取providerID为2、providerType为空、account为admin的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[2], $providerTypes[0], $accounts[2])) && p() && e('0'); // 获取providerID为2、providerType为空、account为test的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[2], $providerTypes[1], $accounts[0])) && p() && e('0'); // 获取providerID为2、providerType为gitlab、account为空的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[2], $providerTypes[1], $accounts[1])) && p() && e('0'); // 获取providerID为2、providerType为gitlab、account为admin的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[2], $providerTypes[1], $accounts[2])) && p() && e('0'); // 获取providerID为2、providerType为gitlab、account为test的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[2], $providerTypes[2], $accounts[0])) && p() && e('0'); // 获取providerID为2、providerType为test、account为空的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[2], $providerTypes[2], $accounts[1])) && p() && e('0'); // 获取providerID为2、providerType为test、account为admin的openID
r($pipelineTester->getOpenIdByAccountTest($providerIds[2], $providerTypes[2], $accounts[2])) && p() && e('0'); // 获取providerID为2、providerType为test、account为test的openID
