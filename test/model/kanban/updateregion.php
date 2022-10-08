#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->updateRegion();
cid=1
pid=1

测试修改区域1的信息 >> name,默认区域,测试修改区域名称1
测试修改区域2的信息 >> name,默认区域,测试修改区域名称2
测试修改区域3的信息 >> name,默认区域,测试修改区域名称3
测试修改区域4的信息 >> 『区域名称』不能为空。
测试修改区域5的信息 >> 『区域名称』不能为空。

*/

$regionIDList = array('1', '2', '3', '4', '5');
$nameList     = array('测试修改区域名称1', '测试修改区域名称2', '测试修改区域名称3', '', '  ');

$kanban = new kanbanTest();

r($kanban->updateRegionTest($regionIDList[0], $nameList[0])) && p('0:field,old,new') && e('name,默认区域,测试修改区域名称1'); // 测试修改区域1的信息
r($kanban->updateRegionTest($regionIDList[1], $nameList[1])) && p('0:field,old,new') && e('name,默认区域,测试修改区域名称2'); // 测试修改区域2的信息
r($kanban->updateRegionTest($regionIDList[2], $nameList[2])) && p('0:field,old,new') && e('name,默认区域,测试修改区域名称3'); // 测试修改区域3的信息
r($kanban->updateRegionTest($regionIDList[3], $nameList[3])) && p('name:0')          && e('『区域名称』不能为空。');          // 测试修改区域4的信息
r($kanban->updateRegionTest($regionIDList[4], $nameList[4])) && p('name:0')          && e('『区域名称』不能为空。');          // 测试修改区域5的信息