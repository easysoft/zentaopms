#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->getByID();
cid=1
pid=1

测试获取module 1821 的信息 >> 1,产品模块1,模块简称1
测试获取module 1822 的信息 >> 1,产品模块2,模块简称2
测试获取module 1981 的信息 >> 41,产品模块161,模块简称161
测试获取module 1982 的信息 >> 41,产品模块162,模块简称162
测试获取module 1621 的信息 >> 634,模块1601,模块简称1601
测试获取module 1622 的信息 >> 634,模块1602,模块简称1602
测试获取module 21 的信息 >> 101,模块1,模块简称1
测试获取module 22 的信息 >> 101,模块2,模块简称2

*/
$moduleID = array(1821, 1822, 1981, 1982, 1621, 1622, 21, 22);

$tree = new treeTest();

r($tree->getByIDTest($moduleID[0])) && p('root,name,short') && e('1,产品模块1,模块简称1');      // 测试获取module 1821 的信息
r($tree->getByIDTest($moduleID[1])) && p('root,name,short') && e('1,产品模块2,模块简称2');      // 测试获取module 1822 的信息
r($tree->getByIDTest($moduleID[2])) && p('root,name,short') && e('41,产品模块161,模块简称161'); // 测试获取module 1981 的信息
r($tree->getByIDTest($moduleID[3])) && p('root,name,short') && e('41,产品模块162,模块简称162'); // 测试获取module 1982 的信息
r($tree->getByIDTest($moduleID[4])) && p('root,name,short') && e('634,模块1601,模块简称1601');  // 测试获取module 1621 的信息
r($tree->getByIDTest($moduleID[5])) && p('root,name,short') && e('634,模块1602,模块简称1602');  // 测试获取module 1622 的信息
r($tree->getByIDTest($moduleID[6])) && p('root,name,short') && e('101,模块1,模块简称1');        // 测试获取module 21 的信息
r($tree->getByIDTest($moduleID[7])) && p('root,name,short') && e('101,模块2,模块简称2');        // 测试获取module 22 的信息