#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodemodel->getImageByID().
timeout=0
cid=19832

- 测试获取 node id 1 的快照
 - 属性id @1
 - 属性name @defaultSnap
 - 属性from @snapshot
 - 属性status @creating
 - 属性createdBy @system
- 测试获取 node id 2 的快照
 - 属性id @2
 - 属性name @defaultSnap
 - 属性from @snapshot
 - 属性status @creating
 - 属性createdBy @admin
- 测试获取 node id 5 的快照
 - 属性id @5
 - 属性name @defaultSnap
 - 属性from @snapshot
 - 属性status @wait
 - 属性createdBy @system
- 测试获取 空的 node id 0 的快照 @0
- 测试获取 不存在的 node id 1000 的快照 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('image')->loadYaml('image')->gen(5);

su('admin');

$zanode = new zanodeModelTest();

$nodeID = array(1, 2, 5, 0, 1000);

r($zanode->getImageByID($nodeID[0])) && p('id,name,from,status,createdBy') && e('1,defaultSnap,snapshot,creating,system'); // 测试获取 node id 1 的快照
r($zanode->getImageByID($nodeID[1])) && p('id,name,from,status,createdBy') && e('2,defaultSnap,snapshot,creating,admin');  // 测试获取 node id 2 的快照
r($zanode->getImageByID($nodeID[2])) && p('id,name,from,status,createdBy') && e('5,defaultSnap,snapshot,wait,system');     // 测试获取 node id 5 的快照
r($zanode->getImageByID($nodeID[3])) && p() && e('0'); // 测试获取 空的 node id 0 的快照
r($zanode->getImageByID($nodeID[4])) && p() && e('0'); // 测试获取 不存在的 node id 1000 的快照