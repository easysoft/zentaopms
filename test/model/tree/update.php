#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->update();
cid=1
pid=1

测试更新module 1821 的 root >> 2,0,0,产品模块1,模块简称1
测试更新module 1821 的 root >> 2,0,1822,产品模块1,模块简称1
测试更新module 1821 的 root >> 2,0,1822,修改后的模块名,模块简称1
测试更新module 1821 的 root >> 2,0,1822,修改后的模块名,修改后的简称
测试更新module 1981 的 root >> 41,1,0,产品模块161,模块简称161

*/
$moduleID = array(1821, 1981);

$changeRoot   = array('root' => '2');
$changeParent = array('parent' => 1822);
$changeName   = array('name' => '修改后的模块名');
$changeShort  = array('short' => '修改后的简称');
$changeBranch = array('branch' => 1);

$tree = new treeTest();

r($tree->updateTest($moduleID[0], $changeRoot))   && p('root,branch,parent,name,short') && e('2,0,0,产品模块1,模块简称1');            // 测试更新module 1821 的 root
r($tree->updateTest($moduleID[0], $changeParent)) && p('root,branch,parent,name,short') && e('2,0,1822,产品模块1,模块简称1');         // 测试更新module 1821 的 root
r($tree->updateTest($moduleID[0], $changeName))   && p('root,branch,parent,name,short') && e('2,0,1822,修改后的模块名,模块简称1');    // 测试更新module 1821 的 root
r($tree->updateTest($moduleID[0], $changeShort))  && p('root,branch,parent,name,short') && e('2,0,1822,修改后的模块名,修改后的简称'); // 测试更新module 1821 的 root
r($tree->updateTest($moduleID[1], $changeBranch)) && p('root,branch,parent,name,short') && e('41,1,0,产品模块161,模块简称161');       // 测试更新module 1981 的 root
