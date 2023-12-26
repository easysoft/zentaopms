#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodemodel->getNodeByMac().
cid=1

- 测试获取 mac 地址 mac1 的节点
 - 属性id @1
 - 属性status @offline
- 测试获取 mac 地址 mac1 的节点mac 地址 mac2 的节点
 - 属性id @2
 - 属性status @offline
- 测试获取 mac 地址 mac1 的节点mac 地址 mac3 的节点
 - 属性id @3
 - 属性status @wait
- 测试获取 mac 地址 mac4 的节点
 - 属性id @4
 - 属性status @wait
- 测试获取 mac 地址 mac7 的节点
 - 属性id @7
 - 属性status @wait
- 测试获取 mac 地址 mac10 的节点
 - 属性id @10
 - 属性status @shutoff
- 测试获取 不存在的 mac 地址 mac1000 的节点 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('user')->gen(10);
zdTable('host')->config('host')->gen(12);

su('admin');

$zanode = new zanodeTest();

$mac = array('mac1', 'mac2', 'mac3', 'mac4', 'mac7', 'mac10',  'mac1000');

r($zanode->getNodeByMac($mac[0])) && p('id,status') && e('1,offline');  // 测试获取 mac 地址 mac1 的节点
r($zanode->getNodeByMac($mac[1])) && p('id,status') && e('2,offline');  // 测试获取 mac 地址 mac1 的节点mac 地址 mac2 的节点
r($zanode->getNodeByMac($mac[2])) && p('id,status') && e('3,wait');     // 测试获取 mac 地址 mac1 的节点mac 地址 mac3 的节点
r($zanode->getNodeByMac($mac[3])) && p('id,status') && e('4,wait');     // 测试获取 mac 地址 mac4 的节点
r($zanode->getNodeByMac($mac[4])) && p('id,status') && e('7,wait');     // 测试获取 mac 地址 mac7 的节点
r($zanode->getNodeByMac($mac[5])) && p('id,status') && e('10,shutoff'); // 测试获取 mac 地址 mac10 的节点
r($zanode->getNodeByMac($mac[6])) && p()            && e('0');          // 测试获取 不存在的 mac 地址 mac1000 的节点
