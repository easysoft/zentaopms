#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodemodel->getSnapshotList().
cid=1

- 测试获取 node id 1 id_desc 快照列表 @11:defaultSnap1,failed;1:defaultSnap,creating

- 测试获取 node id 1 id_asc  快照列表 @11:defaultSnap1,failed

- 测试获取 node id 2 id_desc 快照列表 @2:defaultSnap,failed

- 测试获取 node id 2 id_asc  快照列表 @2:defaultSnap,failed

- 测试获取 node id 3 id_desc 快照列表 @13:defaultSnap3,failed;3:defaultSnap,creating

- 测试获取 node id 3 id_asc  快照列表 @13:defaultSnap3,failed

- 测试获取 node id 4 id_desc 快照列表 @14:defaultSnap4,failed

- 测试获取 node id 4 id_asc  快照列表 @14:defaultSnap4,failed

- 测试获取 空的 node id 0 id_desc 快照列表 @0
- 测试获取 空的 node id 0 id_asc  快照列表 @0
- 测试获取 不存在的 node id 1000 id_desc 快照列表 @0
- 测试获取 不存在的 node id 1000 id_asc  快照列表 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('user')->gen(10);
zdTable('image')->config('image')->gen(20);

su('admin');

$zanode = new zanodeTest();

$nodeID  = array(1, 2, 3, 4, 0, 1000);
$orderBy = array('id_desc', 'id_asc');

r($zanode->getSnapshotListTest($nodeID[0], $orderBy[0])) && p() && e('11:defaultSnap1,failed;1:defaultSnap,creating'); // 测试获取 node id 1 id_desc 快照列表
r($zanode->getSnapshotListTest($nodeID[0], $orderBy[1])) && p() && e('11:defaultSnap1,failed');                        // 测试获取 node id 1 id_asc  快照列表
r($zanode->getSnapshotListTest($nodeID[1], $orderBy[0])) && p() && e('2:defaultSnap,failed');                          // 测试获取 node id 2 id_desc 快照列表
r($zanode->getSnapshotListTest($nodeID[1], $orderBy[1])) && p() && e('2:defaultSnap,failed');                          // 测试获取 node id 2 id_asc  快照列表
r($zanode->getSnapshotListTest($nodeID[2], $orderBy[0])) && p() && e('13:defaultSnap3,failed;3:defaultSnap,creating'); // 测试获取 node id 3 id_desc 快照列表
r($zanode->getSnapshotListTest($nodeID[2], $orderBy[1])) && p() && e('13:defaultSnap3,failed');                        // 测试获取 node id 3 id_asc  快照列表
r($zanode->getSnapshotListTest($nodeID[3], $orderBy[0])) && p() && e('14:defaultSnap4,failed');                        // 测试获取 node id 4 id_desc 快照列表
r($zanode->getSnapshotListTest($nodeID[3], $orderBy[1])) && p() && e('14:defaultSnap4,failed');                        // 测试获取 node id 4 id_asc  快照列表
r($zanode->getSnapshotListTest($nodeID[4], $orderBy[0])) && p() && e('0'); // 测试获取 空的 node id 0 id_desc 快照列表
r($zanode->getSnapshotListTest($nodeID[4], $orderBy[1])) && p() && e('0'); // 测试获取 空的 node id 0 id_asc  快照列表
r($zanode->getSnapshotListTest($nodeID[5], $orderBy[0])) && p() && e('0'); // 测试获取 不存在的 node id 1000 id_desc 快照列表
r($zanode->getSnapshotListTest($nodeID[5], $orderBy[1])) && p() && e('0'); // 测试获取 不存在的 node id 1000 id_asc  快照列表
