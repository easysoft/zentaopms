#!/usr/bin/env php
<?php

/**

title=测试 treeModel->update();
timeout=0
cid=1

- 测试更新module 1821 的 root
 - 属性root @2
 - 属性branch @0
 - 属性parent @0
 - 属性name @模块1
 - 属性short @模块简称1
- 测试更新module 1821 的 root
 - 属性root @2
 - 属性branch @0
 - 属性parent @1822
 - 属性name @模块1
 - 属性short @模块简称1
- 测试更新module 1821 的 root
 - 属性root @2
 - 属性branch @0
 - 属性parent @1822
 - 属性name @修改后的模块名
 - 属性short @模块简称1
- 测试更新module 1821 的 root
 - 属性root @2
 - 属性branch @0
 - 属性parent @1822
 - 属性name @修改后的模块名
 - 属性short @修改后的简称
- 测试更新module 1981 的 root
 - 属性root @1
 - 属性branch @1
 - 属性parent @0
 - 属性name @模块2
 - 属性short @模块简称2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(20);

$moduleID = array(1, 2);

$changeRoot   = array('root' => '2');
$changeParent = array('parent' => 1822);
$changeName   = array('name' => '修改后的模块名');
$changeShort  = array('short' => '修改后的简称');
$changeBranch = array('branch' => 1);

$tree = new treeTest();

r($tree->updateTest($moduleID[0], $changeRoot))   && p('root,branch,parent,name,short') && e('2,0,0,模块1,模块简称1');            // 测试更新module 1821 的 root
r($tree->updateTest($moduleID[0], $changeParent)) && p('root,branch,parent,name,short') && e('2,0,1822,模块1,模块简称1');         // 测试更新module 1821 的 root
r($tree->updateTest($moduleID[0], $changeName))   && p('root,branch,parent,name,short') && e('2,0,1822,修改后的模块名,模块简称1');    // 测试更新module 1821 的 root
r($tree->updateTest($moduleID[0], $changeShort))  && p('root,branch,parent,name,short') && e('2,0,1822,修改后的模块名,修改后的简称'); // 测试更新module 1821 的 root
r($tree->updateTest($moduleID[1], $changeBranch)) && p('root,branch,parent,name,short') && e('1,1,0,模块2,模块简称2');       // 测试更新module 1981 的 root