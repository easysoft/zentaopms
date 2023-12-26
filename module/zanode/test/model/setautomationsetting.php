#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 chartModel->isClickable().
cid=1

- 测试自动化设置 node1 新增
 - 属性id @2
 - 属性product @1
 - 属性node @1
 - 属性scriptPath @scriptPath
 - 属性shell @shell
- 测试自动化设置 node2 更新 id 1
 - 属性id @1
 - 属性product @1
 - 属性node @1
 - 属性scriptPath @scriptPath
 - 属性shell @shell
- 测试自动化设置 node3 新增
 - 属性id @3
 - 属性product @1
 - 属性node @1
 - 属性scriptPath @scriptPath
 - 属性shell @shell
- 测试自动化设置 emptyNode 新增第node条的0属性 @『执行节点』不能为空。
- 测试自动化设置 emptyScriptPath 新增第scriptPath条的0属性 @『脚本目录』不能为空。
- 测试自动化设置 allEmpty 新增
 - 第node条的0属性 @『执行节点』不能为空。
 - 第scriptPath条的0属性 @『脚本目录』不能为空。

 */

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('user')->gen(10);
zdTable('automation')->gen(1);

su('admin');

$zanode = new zanodeTest();

$node1 = new stdclass();
$node1->product    = 1;
$node1->node       = 1;
$node1->scriptPath = 'scriptPath';
$node1->shell      = 'shell';

$node2 = new stdclass();
$node2->id         = 1;
$node2->product    = 1;
$node2->node       = 1;
$node2->scriptPath = 'scriptPath';
$node2->shell      = 'shell';

$node3 = new stdclass();
$node3->id         = 3;
$node3->product    = 1;
$node3->node       = 1;
$node3->scriptPath = 'scriptPath';
$node3->shell      = 'shell';

$emptyNode = new stdclass();
$emptyNode->product    = 1;
$emptyNode->node       = 0;
$emptyNode->scriptPath = 'scriptPath';
$emptyNode->shell      = 'shell';

$emptyScriptPath = new stdclass();
$emptyScriptPath->product    = 1;
$emptyScriptPath->node       = 1;
$emptyScriptPath->scriptPath = '';
$emptyScriptPath->shell      = 'shell';

$allEmpty = new stdclass();
$allEmpty->product    = 0;
$allEmpty->id         = 0;
$allEmpty->node       = 0;
$allEmpty->scriptPath = '';
$allEmpty->shell      = '';

r($zanode->setAutomationSettingTest($node1))           && p('id,product,node,scriptPath,shell') && e('2,1,1,scriptPath,shell');                         // 测试自动化设置 node1 新增
r($zanode->setAutomationSettingTest($node2))           && p('id,product,node,scriptPath,shell') && e('1,1,1,scriptPath,shell');                         // 测试自动化设置 node2 更新 id 1
r($zanode->setAutomationSettingTest($node3))           && p('id,product,node,scriptPath,shell') && e('3,1,1,scriptPath,shell');                         // 测试自动化设置 node3 新增
r($zanode->setAutomationSettingTest($emptyNode))       && p('node:0')                           && e('『执行节点』不能为空。');                        // 测试自动化设置 emptyNode 新增
r($zanode->setAutomationSettingTest($emptyScriptPath)) && p('scriptPath:0')                     && e('『脚本目录』不能为空。');                        // 测试自动化设置 emptyScriptPath 新增
r($zanode->setAutomationSettingTest($allEmpty))        && p('node:0;scriptPath:0')              && e('『执行节点』不能为空。;『脚本目录』不能为空。'); // 测试自动化设置 allEmpty 新增
