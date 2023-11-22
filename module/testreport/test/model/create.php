#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testreport.class.php';

zdTable('testreport')->gen(0);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 testreportModel->create();
cid=1
pid=1

*/
$members = 'dev1,dev11';
$cases   = '1,2,3,4';
$title   = array('正常新增', '负责人为空测试', '参与人员为空测试', '');

$testreport = new testreportTest();

$normalReport = array('owner' => 'user3', 'members' => $members, 'title' => $title[0], 'report' => '', 'cases' => $cases) ;
$noOwner      = array('owner' => '',      'members' => $members, 'title' => $title[1], 'report' => '', 'cases' => $cases) ;
$noTitle      = array('owner' => 'user3', 'members' => $members, 'title' => $title[3], 'report' => '', 'cases' => $cases) ;
$noMembers    = array('owner' => 'user3', 'members' => '',       'title' => $title[2], 'report' => '', 'cases' => $cases) ;
$endLtBegin   = array('owner' => 'user3', 'members' => $members, 'title' => $title[0], 'report' => '', 'cases' => $cases, 'begin' => '2017-01-01', 'end' => '2016-01-01') ;
$emptyBegin   = array('owner' => 'user3', 'members' => $members, 'title' => $title[0], 'report' => '', 'cases' => $cases, 'begin' => '') ;
$emptyEnd     = array('owner' => 'user3', 'members' => $members, 'title' => $title[0], 'report' => '', 'cases' => $cases, 'end' => '') ;

r($testreport->createTest($normalReport)) && p('id,title') && e('1,正常新增');                             // 正常新增
r($testreport->createTest($noOwner))      && p('owner:0')  && e('『负责人』不能为空。');                   // 负责人为空测试
r($testreport->createTest($noTitle))      && p('title:0')  && e('『报告标题』不能为空。');                 // 标题为空测试
r($testreport->createTest($noMembers))    && p('id,title') && e('2,参与人员为空测试');                     // 参与人员测试
r($testreport->createTest($endLtBegin))   && p('end:0')    && e('『结束时间』应当不小于『2017-01-01』。'); // 结束时间小于开始时间测试
r($testreport->createTest($emptyBegin))   && p('begin:0')  && e('『开始时间』不能为空。');                 // 开始时间为空测试
r($testreport->createTest($emptyEnd))     && p('end:0')    && e('『结束时间』不能为空。');                 // 结束时间为空测试
