#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodemodel->getAutomationByProduct().
cid=1

- 测试获取 id 1 的自动化设置
 - 属性id @1
 - 属性product @1
 - 属性node @1
 - 属性scriptPath @scriptPath1
 - 属性shell @shell1
- 测试获取 id 3 的自动化设置
 - 属性id @3
 - 属性product @3
 - 属性node @2
 - 属性scriptPath @scriptPath3
 - 属性shell @shell3
- 测试获取 id 5 的自动化设置
 - 属性id @5
 - 属性product @5
 - 属性node @3
 - 属性scriptPath @scriptPath5
 - 属性shell @shell5
- 测试获取 空的 id 0 的自动化设置 @0
- 测试获取 不存在的 id 111 的自动化设置 @0

 */

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('user')->gen(10);
zdTable('automation')->gen(5);

su('admin');

$zanode = new zanodeTest();

$id = array(1, 3, 5, 0, 111);

r($zanode->getAutomationByID($id[0])) && p('id,product,node,scriptPath,shell') && e('1,1,1,scriptPath1,shell1'); // 测试获取 id 1 的自动化设置
r($zanode->getAutomationByID($id[1])) && p('id,product,node,scriptPath,shell') && e('3,3,2,scriptPath3,shell3'); // 测试获取 id 3 的自动化设置
r($zanode->getAutomationByID($id[2])) && p('id,product,node,scriptPath,shell') && e('5,5,3,scriptPath5,shell5'); // 测试获取 id 5 的自动化设置
r($zanode->getAutomationByID($id[3])) && p()                                   && e('0');                        // 测试获取 空的 id 0 的自动化设置
r($zanode->getAutomationByID($id[4])) && p()                                   && e('0');                        // 测试获取 不存在的 id 111 的自动化设置
