#!/usr/bin/env php
<?php

/**

title=测试 zanodeModel::deleteSnapshot();
timeout=0
cid=19824

- 测试步骤1：删除存在的快照ID=1 >> 期望返回API调用失败错误
- 测试步骤2：删除不存在的快照ID=999 >> 期望返回空值（快照不存在）
- 测试步骤3：删除存在的快照ID=2 >> 期望返回API调用失败错误
- 测试步骤4：删除存在的快照ID=3 >> 期望返回API调用失败错误
- 测试步骤5：删除无效的快照ID=0 >> 期望返回空值（参数无效）

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

$host = zenData('host');
$host->id->range('1-3');
$host->name->range('test-node1,test-node2,test-node3');
$host->type->range('node{3}');
$host->extranet->range('192.168.1.1,192.168.1.2,192.168.1.3');
$host->zap->range('8080{3}');
$host->tokenSN->range('token123,token456,token789');
$host->gen(3);

$image = zenData('image');
$image->id->range('1-6');
$image->host->range('1,2,3,1,2,3');
$image->name->range('test-snapshot1,test-snapshot2,test-snapshot3,test-snapshot4,test-snapshot5,test-snapshot6');
$image->status->range('completed{6}');
$image->from->range('snapshot{6}');
$image->gen(6);

su('admin');

$zanodeTest = new zanodeTest();

r($zanodeTest->deleteSnapshotTest(1)) && p() && e('fail');
r($zanodeTest->deleteSnapshotTest(999)) && p() && e('~~');
r($zanodeTest->deleteSnapshotTest(2)) && p() && e('fail');
r($zanodeTest->deleteSnapshotTest(3)) && p() && e('fail');
r($zanodeTest->deleteSnapshotTest(0)) && p() && e('~~');