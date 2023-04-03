#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->create();
cid=1
pid=1

测试创建用例 工作量占比非数字 >> "工作量比例"必须为数字
测试创建用例 计划开始为空 >> 『计划开始』日期不能为空
测试创建用例 计划开始非日期 >> 『计划开始』应当为合法的日期
测试创建用例 计划完成为空 >> 『计划完成』日期不能为空
测试创建用例 计划完成非日期 >> 『计划完成』应当为合法的日期
测试创建用例 计划完成小于计划开始 >> "计划完成时间"必须大于"计划开始时间"
测试创建用例 计划开始小于项目开始日期 >> 阶段的开始时间不能小于所属项目的开始时间
测试创建用例 计划完成大于项目计划完成 >> 阶段的结束时间不能大于所属项目的结束时间
测试创建用例 工作量占比之和大于100 >> 工作量占比累计不应当超过100%
测试正常更新阶段信息 获取阶段数量 >> 7
测试正常创建一条阶段信息 获取阶段数量 >> 8

*/

$percentNumber   = array('percents' => array('a', '0', '0', '0', '0', '0', '0', '', '', '', '', ''));
$emptyBegin      = array('begin' => array('', '2022-03-13', '2022-03-16', '2022-03-16', '2022-03-19', '2022-03-19', '2022-03-22', '', '', '', '', ''));
$checkBegin      = array('begin' => array('a', '2022-03-13', '2022-03-16', '2022-03-16', '2022-03-19', '2022-03-19', '2022-03-22', '', '', '', '', ''));
$emptyEnd        = array('end' => array('', '2022-04-30', '2022-05-18', '2022-05-18', '2022-04-30', '2022-05-18', '2022-05-18', '', '', '', '', ''));
$checkEnd        = array('end' => array('a', '2022-04-30', '2022-05-18', '2022-05-18', '2022-04-30', '2022-05-18', '2022-05-18', '', '', '', '', ''));
$planFinishSmall = array('end' => array('2022-03-12', '2022-04-30', '2022-05-18', '2022-05-18', '2022-04-30', '2022-05-18', '2022-05-18', '', '', '', '', ''));
$errorBegin      = array('begin' => array('2022-01-01', '2022-03-13', '2022-03-16', '2022-03-16', '2022-03-19', '2022-03-19', '2022-03-22', '', '', '', '', ''));
$errorEnd        = array('end' => array('2100-06-18', '2022-04-30', '2022-05-18', '2022-05-18', '2022-04-30', '2022-05-18', '2022-05-18', '', '', '', '', ''));
$percentOver     = array('percents' => array('50', '51', '0', '0', '0', '0', '0', '', '', '', '', ''));

$names    = array('阶段31', '阶段121', '阶段211', '阶段301', '阶段391', '阶段481', '阶段571', '新增的阶段', '', '', '', '');
$percents = array('0', '0', '0', '0', '0', '0', '0', '0', '', '', '', '');
$begin    = array();
$end      = array();
$create   = array('names' => $names, 'percents' => $percents, 'begin' => $begin, 'end' => $end);

$programplan = new programplanTest();

r($programplan->createTest($percentNumber))   && p() && e('"工作量比例"必须为数字');                   // 测试创建用例 工作量占比非数字
r($programplan->createTest($emptyBegin))      && p() && e('『计划开始』日期不能为空');                 // 测试创建用例 计划开始为空
r($programplan->createTest($checkBegin))      && p() && e('『计划开始』应当为合法的日期');             // 测试创建用例 计划开始非日期
r($programplan->createTest($emptyEnd))        && p() && e('『计划完成』日期不能为空');                 // 测试创建用例 计划完成为空
r($programplan->createTest($checkEnd))        && p() && e('『计划完成』应当为合法的日期');             // 测试创建用例 计划完成非日期
r($programplan->createTest($planFinishSmall)) && p() && e('"计划完成时间"必须大于"计划开始时间"');     // 测试创建用例 计划完成小于计划开始
r($programplan->createTest($errorBegin))      && p() && e("阶段的开始时间不能小于所属项目的开始时间"); // 测试创建用例 计划开始小于项目开始日期
r($programplan->createTest($errorEnd))        && p() && e("阶段的结束时间不能大于所属项目的结束时间"); // 测试创建用例 计划完成大于项目计划完成
r($programplan->createTest($percentOver))     && p() && e('工作量占比累计不应当超过100%');             // 测试创建用例 工作量占比之和大于100
r($programplan->createTest())                 && p() && e('7');                                        // 测试正常更新阶段信息 获取阶段数量
r($programplan->createTest($create))          && p() && e('8');                                        // 测试正常创建一条阶段信息 获取阶段数量
