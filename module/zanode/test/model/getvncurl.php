#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 zanodeModel->getVncUrl().
cid=1

- 测试执行节点parent为0 @0
- 测试执行节点vnc为0 @0
- 测试正常连接
 - 属性hostIP @10.0.1.222
 - 属性agentPort @55001
 - 属性vnc @5900

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->config('host')->gen(3);
zdTable('image')->config('image')->gen(3);
zdTable('user')->gen(1);
su('admin');

$zanode = new zanodeTest();
r($zanode->getVncUrlTest(1)) && p() && e('0'); //测试执行节点parent为0
r($zanode->getVncUrlTest(2)) && p() && e('0'); //测试执行节点vnc为0
r($zanode->getVncUrlTest(3)) && p('hostIP,agentPort,vnc') && e('10.0.1.222,55001,5900'); //测试正常连接
