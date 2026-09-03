#!/usr/bin/env php
<?php

/**

title=测试 searchTao::replaceDynamic();
timeout=0
cid=18343

- 测试替换 $lastWeek @1
- 测试替换 $thisWeek @1
- 测试替换 $lastMonth @1
- 测试替换 $thisMonth @1
- 测试替换 $yesterday @1
- 测试替换 $today @1
- 测试替换me @1
- 测试不包含$变量的查询 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

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
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(1);
su('admin');
$app->loadClass('date');

// 准备测试数据
$queryList = array();
$queryList[] = "date between '\$lastWeek'";
$queryList[] = "date between '\$thisWeek'";
$queryList[] = "date between '\$lastMonth'";
$queryList[] = "date between '\$thisMonth'";
$queryList[] = "date between '\$yesterday'";
$queryList[] = "date between '\$today'";
$queryList[] = "account = '\$@me'";
$queryList[] = "title like 'normal query'";

$lastWeek  = date::getLastWeek();
$thisWeek  = date::getThisWeek();
$lastMonth = date::getLastMonth();
$thisMonth = date::getThisMonth();
$yesterday = date::yesterday();
$today     = date(DT_DATE1);

$expectedList = array();
$expectedList[] = "date between '{$lastWeek['begin']}' and '{$lastWeek['end']}'";
$expectedList[] = "date between '{$thisWeek['begin']}' and '{$thisWeek['end']}'";
$expectedList[] = "date between '{$lastMonth['begin']}' and '{$lastMonth['end']}'";
$expectedList[] = "date between '{$thisMonth['begin']}' and '{$thisMonth['end']}'";
$expectedList[] = "date between '{$yesterday} 00:00:00' and '{$yesterday} 23:59:59'";
$expectedList[] = "date between '{$today} 00:00:00' and '{$today} 23:59:59'";
$expectedList[] = "account = 'admin'";
$expectedList[] = "title like 'normal query'";

// 创建测试实例
$search = new searchTaoTest();

// 执行测试步骤
r($search->replaceDynamicTest($queryList[0]) === $expectedList[0]) && p() && e('1'); // 测试替换 $lastWeek
r($search->replaceDynamicTest($queryList[1]) === $expectedList[1]) && p() && e('1'); // 测试替换 $thisWeek
r($search->replaceDynamicTest($queryList[2]) === $expectedList[2]) && p() && e('1'); // 测试替换 $lastMonth
r($search->replaceDynamicTest($queryList[3]) === $expectedList[3]) && p() && e('1'); // 测试替换 $thisMonth
r($search->replaceDynamicTest($queryList[4]) === $expectedList[4]) && p() && e('1'); // 测试替换 $yesterday
r($search->replaceDynamicTest($queryList[5]) === $expectedList[5]) && p() && e('1'); // 测试替换 $today
r($search->replaceDynamicTest($queryList[6]) === $expectedList[6]) && p() && e('1'); // 测试替换me
r($search->replaceDynamicTest($queryList[7]) === $expectedList[7]) && p() && e('1'); // 测试不包含$变量的查询
