#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodemodel->getNodeByID().
cid=1

- 测试获取 id 1 的节点
 - 属性id @1
 - 属性status @offline
- 测试获取 id 1 的节点id 2 的节点
 - 属性id @2
 - 属性status @offline
- 测试获取 id 1 的节点id 3 的节点
 - 属性id @3
 - 属性status @wait
- 测试获取 id 4 的节点
 - 属性id @4
 - 属性status @wait
- 测试获取 id 7 的节点
 - 属性id @7
 - 属性status @wait
- 测试获取 id 10 的节点
 - 属性id @10
 - 属性status @shutoff
- 测试获取 不存在的 id 1000 的节点 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('user')->gen(10);
zdTable('host')->config('host')->gen(12);

su('admin');

$zanode = new zanodeTest();

$id = array(1, 2, 3, 4, 7, 10,  1000);

r($zanode->getNodeByID($id[0])) && p('id,status') && e('1,offline');  // 测试获取 id 1 的节点
r($zanode->getNodeByID($id[1])) && p('id,status') && e('2,offline');  // 测试获取 id 1 的节点id 2 的节点
r($zanode->getNodeByID($id[2])) && p('id,status') && e('3,wait');     // 测试获取 id 1 的节点id 3 的节点
r($zanode->getNodeByID($id[3])) && p('id,status') && e('4,wait');     // 测试获取 id 4 的节点
r($zanode->getNodeByID($id[4])) && p('id,status') && e('7,wait');     // 测试获取 id 7 的节点
r($zanode->getNodeByID($id[5])) && p('id,status') && e('10,shutoff'); // 测试获取 id 10 的节点
r($zanode->getNodeByID($id[6])) && p()            && e('0');          // 测试获取 不存在的 id 1000 的节点
