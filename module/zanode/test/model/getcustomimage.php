#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodemodel->getCustomImage().
cid=1

- 测试获取 node id 1 状态 creating,wait id_desc 快照列表
 - 属性id @13
 - 属性from @1
 - 属性status @creating
- 测试获取 node id 1 状态 creating,wait id_asc 快照列表
 - 属性id @1
 - 属性from @1
 - 属性status @creating
- 测试获取 node id 1 状态 array('wait') id_desc 快照列表 @0
- 测试获取 node id 1 状态 array('wait') id_asc 快照列表 @0
- 测试获取 node id 2 状态 creating,wait id_desc 快照列表
 - 属性id @14
 - 属性from @2
 - 属性status @creating
- 测试获取 node id 2 状态 creating,wait id_asc 快照列表
 - 属性id @2
 - 属性from @2
 - 属性status @creating
- 测试获取 node id 2 状态 array('wait') id_desc 快照列表 @0
- 测试获取 node id 2 状态 array('wait') id_asc 快照列表 @0
- 测试获取 node id 3 状态 creating,wait id_desc 快照列表
 - 属性id @15
 - 属性from @3
 - 属性status @wait
- 测试获取 node id 3 状态 creating,wait id_asc 快照列表
 - 属性id @3
 - 属性from @3
 - 属性status @creating
- 测试获取 node id 3 状态 array('wait') id_desc 快照列表
 - 属性id @15
 - 属性from @3
 - 属性status @wait
- 测试获取 node id 3 状态 array('wait') id_asc 快照列表
 - 属性id @15
 - 属性from @3
 - 属性status @wait
- 测试获取 空的 node id 0 状态 creating,wait id_desc 快照列表 @0
- 测试获取 空的 node id 0 状态 creating,wait id_asc 快照列表 @0
- 测试获取 空的 node id 0 状态 array('wait') id_desc 快照列表 @0
- 测试获取 空的 node id 0 状态 array('wait') id_asc 快照列表 @0
- 测试获取 不存在的 node id 1000 状态 creating,wait id_desc 快照列表 @0
- 测试获取 不存在的 node id 1000 状态 creating,wait id_asc 快照列表 @0
- 测试获取 不存在的 node id 1000 状态 array('wait') id_desc 快照列表 @0
- 测试获取 不存在的 node id 1000 状态 array('wait') id_asc 快照列表 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('user')->gen(10);
zdTable('image')->config('image')->gen(20);

su('admin');

$zanode = new zanodeTest();

$nodeID  = array(1, 2, 3, 0, 1000);
$orderBy = array('id_desc', 'id_asc');
$status  = array('creating,wait', array('wait'));

r($zanode->getCustomImage($nodeID[0], $status[0], $orderBy[0])) && p('id,from,status') && e('13,1,creating'); // 测试获取 node id 1 状态 creating,wait id_desc 快照列表
r($zanode->getCustomImage($nodeID[0], $status[0], $orderBy[1])) && p('id,from,status') && e('1,1,creating');  // 测试获取 node id 1 状态 creating,wait id_asc 快照列表
r($zanode->getCustomImage($nodeID[0], $status[1], $orderBy[0])) && p()                 && e('0');             // 测试获取 node id 1 状态 array('wait') id_desc 快照列表
r($zanode->getCustomImage($nodeID[0], $status[1], $orderBy[1])) && p()                 && e('0');             // 测试获取 node id 1 状态 array('wait') id_asc 快照列表
r($zanode->getCustomImage($nodeID[1], $status[0], $orderBy[0])) && p('id,from,status') && e('14,2,creating'); // 测试获取 node id 2 状态 creating,wait id_desc 快照列表
r($zanode->getCustomImage($nodeID[1], $status[0], $orderBy[1])) && p('id,from,status') && e('2,2,creating');  // 测试获取 node id 2 状态 creating,wait id_asc 快照列表
r($zanode->getCustomImage($nodeID[1], $status[1], $orderBy[0])) && p()                 && e('0');             // 测试获取 node id 2 状态 array('wait') id_desc 快照列表
r($zanode->getCustomImage($nodeID[1], $status[1], $orderBy[1])) && p()                 && e('0');             // 测试获取 node id 2 状态 array('wait') id_asc 快照列表
r($zanode->getCustomImage($nodeID[2], $status[0], $orderBy[0])) && p('id,from,status') && e('15,3,wait');     // 测试获取 node id 3 状态 creating,wait id_desc 快照列表
r($zanode->getCustomImage($nodeID[2], $status[0], $orderBy[1])) && p('id,from,status') && e('3,3,creating');  // 测试获取 node id 3 状态 creating,wait id_asc 快照列表
r($zanode->getCustomImage($nodeID[2], $status[1], $orderBy[0])) && p('id,from,status') && e('15,3,wait');     // 测试获取 node id 3 状态 array('wait') id_desc 快照列表
r($zanode->getCustomImage($nodeID[2], $status[1], $orderBy[1])) && p('id,from,status') && e('15,3,wait');     // 测试获取 node id 3 状态 array('wait') id_asc 快照列表
r($zanode->getCustomImage($nodeID[3], $status[0], $orderBy[0])) && p()                 && e('0');             // 测试获取 空的 node id 0 状态 creating,wait id_desc 快照列表
r($zanode->getCustomImage($nodeID[3], $status[0], $orderBy[1])) && p()                 && e('0');             // 测试获取 空的 node id 0 状态 creating,wait id_asc 快照列表
r($zanode->getCustomImage($nodeID[3], $status[1], $orderBy[0])) && p()                 && e('0');             // 测试获取 空的 node id 0 状态 array('wait') id_desc 快照列表
r($zanode->getCustomImage($nodeID[3], $status[1], $orderBy[1])) && p()                 && e('0');             // 测试获取 空的 node id 0 状态 array('wait') id_asc 快照列表
r($zanode->getCustomImage($nodeID[4], $status[0], $orderBy[0])) && p()                 && e('0');             // 测试获取 不存在的 node id 1000 状态 creating,wait id_desc 快照列表
r($zanode->getCustomImage($nodeID[4], $status[0], $orderBy[1])) && p()                 && e('0');             // 测试获取 不存在的 node id 1000 状态 creating,wait id_asc 快照列表
r($zanode->getCustomImage($nodeID[4], $status[1], $orderBy[0])) && p()                 && e('0');             // 测试获取 不存在的 node id 1000 状态 array('wait') id_desc 快照列表
r($zanode->getCustomImage($nodeID[4], $status[1], $orderBy[1])) && p()                 && e('0');             // 测试获取 不存在的 node id 1000 状态 array('wait') id_asc 快照列表
