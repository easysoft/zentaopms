#!/usr/bin/env php
<?php

/**

title=测试 zahostModel::getPairs();
timeout=0
cid=19751

- 测试步骤1：查询正常的zahost类型宿主机键值对
 - 属性1 @宿主机A
 - 属性2 @宿主机B
 - 属性3 @宿主机C
 - 属性7 @宿主机D
 - 属性8 @宿主机E
- 测试步骤2：验证已删除的宿主机被过滤属性4 @~~
- 测试步骤3：验证非zahost类型的主机被过滤
 - 属性5 @~~
 - 属性6 @~~
- 测试步骤4：验证虚拟主机类型被过滤
 - 属性9 @~~
 - 属性10 @~~
- 测试步骤5：验证返回的有效记录数量 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$host = zenData('host');
$host->id->range('1-10');
$host->name->range('宿主机A,宿主机B,宿主机C,已删除宿主机,普通主机1,普通主机2,宿主机D,宿主机E,虚拟主机1,虚拟主机2');
$host->type->range('zahost{4},normal{2},zahost{2},vhost{2}');
$host->deleted->range('0{3},1,0{6}');
$host->group->range('group1,group3,group2,group1,group1,group2,group1,group2,group1,group3');
$host->gen(10);

su('admin');

$zahost = new zahostModelTest();

r($zahost->getPairsTest()) && p('1,2,3,7,8') && e('宿主机A,宿主机B,宿主机C,宿主机D,宿主机E'); // 测试步骤1：查询正常的zahost类型宿主机键值对
r($zahost->getPairsTest()) && p('4') && e('~~'); // 测试步骤2：验证已删除的宿主机被过滤
r($zahost->getPairsTest()) && p('5,6') && e('~~,~~'); // 测试步骤3：验证非zahost类型的主机被过滤
r($zahost->getPairsTest()) && p('9,10') && e('~~,~~'); // 测试步骤4：验证虚拟主机类型被过滤
r(count($zahost->getPairsTest())) && p() && e('5'); // 测试步骤5：验证返回的有效记录数量