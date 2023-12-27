#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 zanodeModel->deleteSnapshot().
cid=1

- 测试删除不存在的快照 @exit status 1, error: Domain snapshot not found: no domain snapshot with matching name 'snapshot3'

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->config('host')->gen(2);
zdTable('image')->config('image')->gen(3);
zdTable('user')->gen(1);
su('admin');

$zanode = new zanodeTest();
r($zanode->deleteSnapshotTest(3)) && p() && e("exit status 1, error: Domain snapshot not found: no domain snapshot with matching name 'snapshot3'"); //测试删除不存在的快照
