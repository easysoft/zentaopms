#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->getModulesName();
cid=1
pid=1

测试获取module 1821 1981 的module name >> /产品模块1;/产品模块161
测试获取module 1982 1621 的module name >> /产品模块162;/模块1601
测试获取module 1822 22 的module name >> /产品模块2;/模块2
测试获取module 1622 21 的module name >> /模块1602;/模块1

*/
$moduleIDList = array(array(1821, 1981), array(1982, 1621), array(1822, 22), array(1622, 21));

$tree = new treeTest();

r($tree->getModulesNameTest($moduleIDList[0])) && p('1821;1981') && e('/产品模块1;/产品模块161'); // 测试获取module 1821 1981 的module name
r($tree->getModulesNameTest($moduleIDList[1])) && p('1982;1621') && e('/产品模块162;/模块1601');  // 测试获取module 1982 1621 的module name
r($tree->getModulesNameTest($moduleIDList[2])) && p('1822;22')   && e('/产品模块2;/模块2');       // 测试获取module 1822 22 的module name
r($tree->getModulesNameTest($moduleIDList[3])) && p('1622;21')   && e('/模块1602;/模块1');        // 测试获取module 1622 21 的module name