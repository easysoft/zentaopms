#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
$zd_company = zenData('company');
$zd_company->id->range('1');
$zd_company->name->range('禅道软件');
$zd_company->admins->range('admin');
$zd_company->guest->range('0');
$zd_company->gen(1);
$dbh->exec("UPDATE zt_company SET admins = ',admin,' WHERE id = 1");
global $app;
$app->company = $dbh->query('SELECT * FROM zt_company WHERE id = 1')->fetch(PDO::FETCH_OBJ);

$zd_user = zenData('user');
$zd_user->id->range('1-6');
$zd_user->account->range('admin,po15,po16,user1,user2,user3');
$zd_user->realname->range('admin,po15,po16,user1,user2,user3');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(6);

su('admin');

zenData('kanban')->gen(10);
zenData('kanbanregion')->gen(5);
zenData('kanbancell')->gen(100);
zenData('kanbancolumn')->gen(100);
zenData('kanbanlane')->gen(5);
zenData('kanbangroup')->gen(200);

/**

title=测试 kanbanModel->copyRegions();
timeout=0
cid=16884

- 复制默认区域1，查看相关字段属性6 @默认区域
- 复制默认区域2，查看相关字段属性7 @默认区域
- 复制默认区域3，查看相关字段属性8 @默认区域
- 复制默认区域4，查看相关字段属性9 @默认区域
- 复制默认区域5，查看相关字段属性10 @默认区域
- 查看复制出来的区域下的分组数量 @5
- 查看复制出来的区域下的分组数量 @5
- 查看复制出来的区域下的分组数量 @5
- 查看复制出来的区域下的分组数量 @5
- 查看复制出来的区域下的分组数量 @5
- 查看复制出来的区域下的泳道数量 @13
- 查看复制出来的区域下的泳道数量 @13
- 查看复制出来的区域下的泳道数量 @13
- 查看复制出来的区域下的泳道数量 @13
- 查看复制出来的区域下的泳道数量 @13
- 查看复制出来的区域下的看板列数量 @178
- 查看复制出来的区域下的看板列数量 @178
- 查看复制出来的区域下的看板列数量 @178
- 查看复制出来的区域下的看板列数量 @178
- 查看复制出来的区域下的看板列数量 @178

*/

global $tester;
$tester->loadModel('kanban');

$kanban1 = $tester->kanban->getById(6);
$kanban2 = $tester->kanban->getById(7);
$kanban3 = $tester->kanban->getById(8);
$kanban4 = $tester->kanban->getById(9);
$kanban5 = $tester->kanban->getById(10);

$kanban = new kanbanModelTest();

r($kanban->copyRegionsTest($kanban1, 1,  'kanban')) && p('6')  && e('默认区域'); // 复制默认区域1，查看相关字段
r($kanban->copyRegionsTest($kanban2, 2,  'kanban')) && p('7')  && e('默认区域'); // 复制默认区域2，查看相关字段
r($kanban->copyRegionsTest($kanban3, 3,  'kanban')) && p('8')  && e('默认区域'); // 复制默认区域3，查看相关字段
r($kanban->copyRegionsTest($kanban4, 4,  'kanban')) && p('9')  && e('默认区域'); // 复制默认区域4，查看相关字段
r($kanban->copyRegionsTest($kanban5, 5,  'kanban')) && p('10') && e('默认区域'); // 复制默认区域5，查看相关字段

r(count($tester->kanban->getGroupGroupByRegions(array(6)),  true)) && p() && e('5'); // 查看复制出来的区域下的分组数量
r(count($tester->kanban->getGroupGroupByRegions(array(7)),  true)) && p() && e('5'); // 查看复制出来的区域下的分组数量
r(count($tester->kanban->getGroupGroupByRegions(array(8)),  true)) && p() && e('5'); // 查看复制出来的区域下的分组数量
r(count($tester->kanban->getGroupGroupByRegions(array(9)),  true)) && p() && e('5'); // 查看复制出来的区域下的分组数量
r(count($tester->kanban->getGroupGroupByRegions(array(10)), true)) && p() && e('5'); // 查看复制出来的区域下的分组数量

r(count($tester->kanban->getLaneGroupByRegions(array(6)),  true)) && p() && e('13'); // 查看复制出来的区域下的泳道数量
r(count($tester->kanban->getLaneGroupByRegions(array(7)),  true)) && p() && e('13'); // 查看复制出来的区域下的泳道数量
r(count($tester->kanban->getLaneGroupByRegions(array(8)),  true)) && p() && e('13'); // 查看复制出来的区域下的泳道数量
r(count($tester->kanban->getLaneGroupByRegions(array(9)),  true)) && p() && e('13'); // 查看复制出来的区域下的泳道数量
r(count($tester->kanban->getLaneGroupByRegions(array(10)), true)) && p() && e('13'); // 查看复制出来的区域下的泳道数量

r(count($tester->kanban->getColumnGroupByRegions(array(6)),  true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量
r(count($tester->kanban->getColumnGroupByRegions(array(7)),  true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量
r(count($tester->kanban->getColumnGroupByRegions(array(8)),  true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量
r(count($tester->kanban->getColumnGroupByRegions(array(9)),  true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量
r(count($tester->kanban->getColumnGroupByRegions(array(10)), true)) && p() && e('178'); // 查看复制出来的区域下的看板列数量