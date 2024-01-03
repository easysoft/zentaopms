#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/report.class.php';

zdTable('bug')->config('bug')->gen('100');
zdTable('user')->gen(1);

su('admin');

/**

title=测试 reportModel->computePercent();
timeout=0
cid=1

- 测试 bug按照 模块 分组 的百分比 @0:0.91;1821:0.01;1822:0.01;1823:0.01;1825:0.01;1826:0.01;1827:0.01;1831:0.01;1832:0.01;1833:0.01;
- 测试 bug按照 版本 分组 的百分比 @0:0.3333;1:0.6667;
- 测试 bug按照 严重程度 分组 的百分比 @1:0.25;2:0.25;3:0.25;4:0.25;
- 测试 bug按照 解决方案 分组 的百分比 @bydesign:0.12;duplicate:0.2;external:0.12;fixed:0.3;notrepro:0.06;postponed:0.08;willnotfix:0.12;
- 测试 bug按照 优先级 分组 的百分比 @1:0.25;2:0.25;3:0.25;4:0.25;
- 测试 bug按照 类型 分组 的百分比 @automation:0.11;codeerror:0.12;config:0.11;designdefect:0.11;install:0.11;others:0.11;performance:0.11;security:0.11;standard:0.11;

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

ksort($bugsPerModule);
ksort($bugsPerBuild);
ksort($bugsPerSeverity);
ksort($bugsPerResolution);
ksort($bugsPerPri);
ksort($bugsPerType);

r($report->computePercentTest($bugsPerModule))     && p() && e('0:0.91;1821:0.01;1822:0.01;1823:0.01;1825:0.01;1826:0.01;1827:0.01;1831:0.01;1832:0.01;1833:0.01;');                                   // 测试 bug按照 模块 分组 的百分比
r($report->computePercentTest($bugsPerBuild))      && p() && e('0:0.3333;1:0.6667;');                                                                                                                  // 测试 bug按照 版本 分组 的百分比
r($report->computePercentTest($bugsPerSeverity))   && p() && e('1:0.25;2:0.25;3:0.25;4:0.25;');                                                                                                        // 测试 bug按照 严重程度 分组 的百分比
r($report->computePercentTest($bugsPerResolution)) && p() && e('bydesign:0.12;duplicate:0.2;external:0.12;fixed:0.3;notrepro:0.06;postponed:0.08;willnotfix:0.12;');                                   // 测试 bug按照 解决方案 分组 的百分比
r($report->computePercentTest($bugsPerPri))        && p() && e('1:0.25;2:0.25;3:0.25;4:0.25;');                                                                                                        // 测试 bug按照 优先级 分组 的百分比
r($report->computePercentTest($bugsPerType))       && p() && e('automation:0.11;codeerror:0.12;config:0.11;designdefect:0.11;install:0.11;others:0.11;performance:0.11;security:0.11;standard:0.11;'); // 测试 bug按照 类型 分组 的百分比