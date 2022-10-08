#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->computePercent();
cid=1
pid=1

测试 bug按照 模块 分组 的百分比 >> 0:0.9714;1821:0.0032;1822:0.0032;1823:0.0032;1825:0.0032;1826:0.0032;1827:0.0032;1831:0.0032;1832:0.0032;1833:0.003;
测试 bug按照 版本 分组 的百分比 >> 0:0.0095;trunk:0.9238;1:0.019;11:0.019;12:0.0032;13:0.0032;14:0.0032;15:0.0032;16:0.0032;17:0.0032;18:0.0032;19:0.0032;20:0.0031;
测试 bug按照 严重程度 分组 的百分比 >> 1:0.2508;2:0.2508;3:0.2508;4:0.2476;
测试 bug按照 解决方案 分组 的百分比 >> fixed:0.3;duplicate:0.2;bydesign:0.12;external:0.12;willnotfix:0.12;postponed:0.08;notrepro:0.06;
测试 bug按照 优先级 分组 的百分比 >> 1:0.2508;2:0.2508;3:0.2508;4:0.2476;
测试 bug按照 类型 分组 的百分比 >> codeerror:0.1556;config:0.1079;install:0.1079;security:0.1048;performance:0.1048;standard:0.1048;automation:0.1048;designdefect:0.1048;others:0.10

*/
$report = new reportTest();

global $tester;
$bug = $tester->loadModel('bug');

$bugsPerModule     = $bug->getDataOfBugsPerModule();
$bugsPerBuild      = $bug->getDataOfBugsPerBuild();
$bugsPerSeverity   = $bug->getDataOfBugsPerSeverity();
$bugsPerResolution = $bug->getDataOfBugsPerResolution();
$bugsPerPri        = $bug->getDataOfBugsPerPri();
$bugsPerType       = $bug->getDataOfBugsPerType();

r($report->computePercentTest($bugsPerModule))     && p() && e('0:0.9714;1821:0.0032;1822:0.0032;1823:0.0032;1825:0.0032;1826:0.0032;1827:0.0032;1831:0.0032;1832:0.0032;1833:0.003;');                               // 测试 bug按照 模块 分组 的百分比
r($report->computePercentTest($bugsPerBuild))      && p() && e('0:0.0095;trunk:0.9238;1:0.019;11:0.019;12:0.0032;13:0.0032;14:0.0032;15:0.0032;16:0.0032;17:0.0032;18:0.0032;19:0.0032;20:0.0031;');                  // 测试 bug按照 版本 分组 的百分比
r($report->computePercentTest($bugsPerSeverity))   && p() && e('1:0.2508;2:0.2508;3:0.2508;4:0.2476;');                                                                                                               // 测试 bug按照 严重程度 分组 的百分比
r($report->computePercentTest($bugsPerResolution)) && p() && e('fixed:0.3;duplicate:0.2;bydesign:0.12;external:0.12;willnotfix:0.12;postponed:0.08;notrepro:0.06;');                                                  // 测试 bug按照 解决方案 分组 的百分比
r($report->computePercentTest($bugsPerPri))        && p() && e('1:0.2508;2:0.2508;3:0.2508;4:0.2476;');                                                                                                               // 测试 bug按照 优先级 分组 的百分比
r($report->computePercentTest($bugsPerType))       && p() && e('codeerror:0.1556;config:0.1079;install:0.1079;security:0.1048;performance:0.1048;standard:0.1048;automation:0.1048;designdefect:0.1048;others:0.10'); // 测试 bug按照 类型 分组 的百分比