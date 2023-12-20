#!/usr/bin/env php
<?php

/**

title=测试 mrModel::batchSyncMR();
timeout=0
cid=0

- 空的合并请求列表 @0
- 正常的合并请求，更新本地MR的标题和编辑者
 - 第1条的id属性 @1
 - 第1条的title属性 @test-merge（不要关闭或删除）
 - 第1条的editedBy属性 @admin
 - 第2条的id属性 @2
 - 第2条的editedBy属性 @~~
 - 第4条的id属性 @4
 - 第4条的compileStatus属性 @failed
 - 第4条的editedBy属性 @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(3);
$mrYaml = zdTable('mr')->config('mr');
$mrYaml->hostID->range(1);
$mrYaml->needCI->range('0-1');
$mrYaml->jobID->range(1);
$mrYaml->title->range('1-100')->prefix('MR-');
$mrYaml->gen(5);

su('admin', false);

global $tester;
$mrModel = $tester->loadModel('mr');

r($mrModel->batchSyncMR(array())) && p() && e('0'); // 空的合并请求列表

$MRList = $mrModel->getList();
r($mrModel->batchSyncMR($MRList)) && p('1:id,title,editedBy;2:id,editedBy;4:id,compileStatus,editedBy') && e('1,test-merge（不要关闭或删除）,admin;2,~~;4,failed,admin'); //正常的合并请求，更新本地MR的标题和编辑者