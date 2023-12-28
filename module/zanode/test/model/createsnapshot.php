#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 zanodeModel->createSnapshot().
cid=1

- 测试执行节点连接失败时创建快照 @失败

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->config('host')->gen(5);
zdTable('user')->gen(5);
su('admin');

$zanode = new zanodeTest();

$snapshot = array('name' => 'snapshot1', 'desc' => '这是快照1的描述');
r($zanode->createSnapshotTest(1, '127.0.0.1', 0, '', $snapshot)) && p() && e('失败'); //测试执行节点连接失败时创建快照
