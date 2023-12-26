#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 zanodeModel->createSnapshot().
cid=1

- 因为没有固定的服务器连接结果均失败 @失败

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->config('host')->gen(5);
zdTable('user')->gen(5);
su('admin');

$zanode = new zanodeTest();

$snapshot = array('name' => 'snapshot1', 'desc' => '这是快照1的描述');
r($zanode->createSnapshotTest(1, $snapshot)) && p(0) && e('失败'); //因为没有固定的服务器连接结果均失败
