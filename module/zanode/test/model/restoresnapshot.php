#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 zanodeModel->restoreSnapshot().
cid=1

- 测试快照状态错误 @快照不可用
- 测试正常还原属性status @restoring

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->config('host')->gen(2);
zdTable('image')->config('image')->gen(3);
zdTable('user')->gen(1);
su('admin');

$zanode = new zanodeTest();
r($zanode->restoreSnapshotTest(2, 3)) && p(0)        && e('快照不可用'); //测试快照状态错误
r($zanode->restoreSnapshotTest(2, 1)) && p('status') && e('restoring');  //测试正常还原
