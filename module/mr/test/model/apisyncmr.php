#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiSyncMR();
timeout=0
cid=0

- 本地MR，无需同步
 - 属性id @2
 - 属性editedBy @~~
- 正常的合并请求，更新本地MR的标题和编辑者
 - 属性id @1
 - 属性title @test-merge（不要关闭或删除）
 - 属性editedBy @admin
- 有CI的合并请求，更新本地MR的流水线状态和编辑者
 - 属性id @4
 - 属性compileStatus @failed
 - 属性editedBy @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(3);
$mrYaml = zdTable('mr')->config('mr');
$mrYaml->hostID->range(1);
$mrYaml->needCI->range('0-1');
$mrYaml->jobID->range(1);
$mrYaml->title->range('1-100')->prefix('MR-');
$mrYaml->gen(5);

su('admin');

global $tester;
$mrModel = $tester->loadModel('mr');

$localMR = $mrModel->fetchByID(2);
r($mrModel->apiSyncMR($localMR)) && p('id,editedBy') && e('2,~~'); // 本地MR，无需同步

$MR = $mrModel->fetchByID(1);
r($mrModel->apiSyncMR($MR)) && p('id,title,editedBy') && e('1,test-merge（不要关闭或删除）,admin'); //正常的合并请求，更新本地MR的标题和编辑者

$ciMR = $mrModel->fetchByID(4);
r($mrModel->apiSyncMR($ciMR)) && p('id,compileStatus,editedBy') && e('4,failed,admin'); // 有CI的合并请求，更新本地MR的流水线状态和编辑者