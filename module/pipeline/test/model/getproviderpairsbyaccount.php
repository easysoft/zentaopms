#!/usr/bin/env php
<?php
/**

title=测试 pipelineModel->getProviderPairsByAccount();
cid=1

- 获取providerType为空、account为空的providerID @0
- 获取providerType为空、account为user1的providerID @0
- 获取providerType为空、account为test的providerID @0
- 获取providerType为gitlab、account为空的providerID属性1 @1
- 获取providerType为gitlab、account为user1的providerID属性1 @2
- 获取providerType为gitlab、account为test的providerID @0
- 获取providerType为test、account为空的providerID @0
- 获取providerType为test、account为user1的providerID @0
- 获取providerType为test、account为test的providerID @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pipeline.class.php';

zdTable('user')->gen(5);
zdTable('oauth')->config('oauth')->gen(5);

$providerTypes = array('', 'gitlab', 'test');
$accounts      = array('', 'user1', 'test');

$pipelineTester = new pipelineTest();
r($pipelineTester->getProviderPairsByAccountTest($providerTypes[0], $accounts[0])) && p()    && e('0'); // 获取providerType为空、account为空的providerID
r($pipelineTester->getProviderPairsByAccountTest($providerTypes[0], $accounts[1])) && p()    && e('0'); // 获取providerType为空、account为user1的providerID
r($pipelineTester->getProviderPairsByAccountTest($providerTypes[0], $accounts[2])) && p()    && e('0'); // 获取providerType为空、account为test的providerID
r($pipelineTester->getProviderPairsByAccountTest($providerTypes[1], $accounts[0])) && p('1') && e('1'); // 获取providerType为gitlab、account为空的providerID
r($pipelineTester->getProviderPairsByAccountTest($providerTypes[1], $accounts[1])) && p('1') && e('2'); // 获取providerType为gitlab、account为user1的providerID
r($pipelineTester->getProviderPairsByAccountTest($providerTypes[1], $accounts[2])) && p()    && e('0'); // 获取providerType为gitlab、account为test的providerID
r($pipelineTester->getProviderPairsByAccountTest($providerTypes[2], $accounts[0])) && p()    && e('0'); // 获取providerType为test、account为空的providerID
r($pipelineTester->getProviderPairsByAccountTest($providerTypes[2], $accounts[1])) && p()    && e('0'); // 获取providerType为test、account为user1的providerID
r($pipelineTester->getProviderPairsByAccountTest($providerTypes[2], $accounts[2])) && p()    && e('0'); // 获取providerType为test、account为test的providerID
