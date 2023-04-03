#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->changeRoot();
cid=1
pid=1

测试修改module 1821 的root从 1 修改为 2 >> 0
测试修改module 1822 的root从 1 修改为 2 >> 0
测试修改module 1825 的root从 2 修改为 1 >> 0
测试修改module 1826 的root从 2 修改为 1 >> 0
测试修改module 1981 的root从 41 修改为 42 >> 0
测试修改module 1982 的root从 41 修改为 42 >> 0
测试修改module 1985 的root从 42 修改为 41 >> 0
测试修改module 1986 的root从 42 修改为 41 >> 0
测试修改module 21 的root从 101 修改为 102 >> 0
测试修改module 22 的root从 101 修改为 102 >> 0
测试修改module 24 的root从 102 修改为 101 >> 0
测试修改module 25 的root从 102 修改为 101 >> 0

*/
$moduleID = array(1821, 1822, 1825, 1826, 1981, 1982, 1985, 1986, 21, 22, 24, 25);
$root     = array(1, 2, 41, 42, 101, 102);
$type     = array('story', 'task');

$tree = new treeTest();

r($tree->changeRootTest($moduleID[0], $root[0], $root[1], $type[0]))  && p('id,root') && e('0'); // 测试修改module 1821 的root从 1 修改为 2
r($tree->changeRootTest($moduleID[1], $root[0], $root[1], $type[0]))  && p('id,root') && e('0'); // 测试修改module 1822 的root从 1 修改为 2
r($tree->changeRootTest($moduleID[2], $root[1], $root[0], $type[0]))  && p('id,root') && e('0'); // 测试修改module 1825 的root从 2 修改为 1
r($tree->changeRootTest($moduleID[3], $root[1], $root[0], $type[0]))  && p('id,root') && e('0'); // 测试修改module 1826 的root从 2 修改为 1
r($tree->changeRootTest($moduleID[4], $root[2], $root[3], $type[0]))  && p('id,root') && e('0'); // 测试修改module 1981 的root从 41 修改为 42
r($tree->changeRootTest($moduleID[5], $root[2], $root[3], $type[0]))  && p('id,root') && e('0'); // 测试修改module 1982 的root从 41 修改为 42
r($tree->changeRootTest($moduleID[6], $root[3], $root[2], $type[0]))  && p('id,root') && e('0'); // 测试修改module 1985 的root从 42 修改为 41
r($tree->changeRootTest($moduleID[7], $root[3], $root[2], $type[0]))  && p('id,root') && e('0'); // 测试修改module 1986 的root从 42 修改为 41
r($tree->changeRootTest($moduleID[8], $root[4], $root[5], $type[1]))  && p('id,root') && e('0'); // 测试修改module 21 的root从 101 修改为 102
r($tree->changeRootTest($moduleID[9], $root[4], $root[5], $type[1]))  && p('id,root') && e('0'); // 测试修改module 22 的root从 101 修改为 102
r($tree->changeRootTest($moduleID[10], $root[5], $root[4], $type[1])) && p('id,root') && e('0'); // 测试修改module 24 的root从 102 修改为 101
r($tree->changeRootTest($moduleID[11], $root[5], $root[4], $type[1])) && p('id,root') && e('0'); // 测试修改module 25 的root从 102 修改为 101
