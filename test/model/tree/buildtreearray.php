#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->buildTreeArray();
cid=1
pid=1

测试创建树选项的字符串长度 1 >> 56
测试创建树选项的字符串长度 2 >> 56;58
测试创建树选项的字符串长度 3 >> 112;58
测试创建树选项的字符串长度 4 >> 112;116
测试创建树选项的字符串长度 5 >> 112;116;148
测试创建树选项的字符串长度 6 >> 112;116;300
测试创建树选项的字符串长度 7 >> 112;116;336
测试创建树选项的字符串长度 8 >> 112;116;372
测试创建树选项的字符串长度 9 >> 112;116;408
测试创建树选项的字符串长度 10 >> 112;116;444
测试创建树选项的字符串长度 11 >> 112;116;480
测试创建树选项的字符串长度 12 >> 112;116;516

*/
$tree = new treeTest();

$treeMenu = array();

$root = array(41, 42, 43, 101, 102, 103);
$type = array('story', 'task');

$stmt    = $tester->dbh->query($tree->buildMenuQueryTest($root[0], $type[0]));
$modules = array();
while($module = $stmt->fetch()) $modules[$module->id] = $module;

$moduleIDList = array_keys($modules);

r($tree->buildTreeArrayTest($treeMenu, $modules, $moduleIDList[0]))  && p('1981')        && e('56');          // 测试创建树选项的字符串长度 1
r($tree->buildTreeArrayTest($treeMenu, $modules, $moduleIDList[1]))  && p('1981;2381')   && e('56;58');       // 测试创建树选项的字符串长度 2
r($tree->buildTreeArrayTest($treeMenu, $modules, $moduleIDList[2]))  && p('1981;2381')   && e('112;58');      // 测试创建树选项的字符串长度 3
r($tree->buildTreeArrayTest($treeMenu, $modules, $moduleIDList[3]))  && p('1981;2381')   && e('112;116');     // 测试创建树选项的字符串长度 4
r($tree->buildTreeArrayTest($treeMenu, $modules, $moduleIDList[4]))  && p('1981;2381;0') && e('112;116;148'); // 测试创建树选项的字符串长度 5
r($tree->buildTreeArrayTest($treeMenu, $modules, $moduleIDList[5]))  && p('1981;2381;0') && e('112;116;300'); // 测试创建树选项的字符串长度 6
r($tree->buildTreeArrayTest($treeMenu, $modules, $moduleIDList[6]))  && p('1981;2381;0') && e('112;116;336'); // 测试创建树选项的字符串长度 7
r($tree->buildTreeArrayTest($treeMenu, $modules, $moduleIDList[7]))  && p('1981;2381;0') && e('112;116;372'); // 测试创建树选项的字符串长度 8
r($tree->buildTreeArrayTest($treeMenu, $modules, $moduleIDList[8]))  && p('1981;2381;0') && e('112;116;408'); // 测试创建树选项的字符串长度 9
r($tree->buildTreeArrayTest($treeMenu, $modules, $moduleIDList[9]))  && p('1981;2381;0') && e('112;116;444'); // 测试创建树选项的字符串长度 10
r($tree->buildTreeArrayTest($treeMenu, $modules, $moduleIDList[10])) && p('1981;2381;0') && e('112;116;480'); // 测试创建树选项的字符串长度 11
r($tree->buildTreeArrayTest($treeMenu, $modules, $moduleIDList[11])) && p('1981;2381;0') && e('112;116;516'); // 测试创建树选项的字符串长度 12