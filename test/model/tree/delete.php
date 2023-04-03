#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->delete();
cid=1
pid=1

测试删除module 1821 >> 1
测试删除module 1822 >> 1
测试删除module 1981 >> 1
测试删除module 1982 >> 1
测试删除module 1621 >> 1
测试删除module 1622 >> 1
测试删除module 21 >> 1
测试删除module 22 >> 1

*/

$moduleID = array(1821, 1822, 1981, 1982, 1621, 1622, 21, 22);

$tree = new treeTest();

ob_start();
r($tree->deleteTest($moduleID[0])) && p('deleted') && e('1'); // 测试删除module 1821
r($tree->deleteTest($moduleID[1])) && p('deleted') && e('1'); // 测试删除module 1822
r($tree->deleteTest($moduleID[2])) && p('deleted') && e('1'); // 测试删除module 1981
r($tree->deleteTest($moduleID[3])) && p('deleted') && e('1'); // 测试删除module 1982
r($tree->deleteTest($moduleID[4])) && p('deleted') && e('1'); // 测试删除module 1621
r($tree->deleteTest($moduleID[5])) && p('deleted') && e('1'); // 测试删除module 1622
r($tree->deleteTest($moduleID[6])) && p('deleted') && e('1'); // 测试删除module 21
r($tree->deleteTest($moduleID[7])) && p('deleted') && e('1'); // 测试删除module 22
ob_end_flush();
