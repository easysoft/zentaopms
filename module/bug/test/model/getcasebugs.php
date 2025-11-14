#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('bug')->loadYaml('casebug')->gen(20);

/**

title=bugModel->getCaseBugs();
timeout=0
cid=15363

- 测试获取runID为0 caseID为2的bug @测试单转Bug1,测试单转Bug11

- 测试获取runID为0 caseID为6的bug @SonarQube_Bug2,SonarQube_Bug12

- 测试获取runID为0 caseID为10的bug @测试单转Bug3,测试单转Bug13

- 测试获取runID为0 caseID不存在的bug @0
- 测试获取runID为2 caseID为0的bug @测试单转Bug1,测试单转Bug11

- 测试获取runID为2 caseID为2的bug @测试单转Bug1,测试单转Bug11

- 测试获取runID为2 caseID为6的bug @测试单转Bug1,测试单转Bug11

- 测试获取runID为2 caseID为10的bug @测试单转Bug1,测试单转Bug11

- 测试获取runID为2 caseID不存在的bug @测试单转Bug1,测试单转Bug11

- 测试获取runID为6 caseID为0的bug @SonarQube_Bug2,SonarQube_Bug12

- 测试获取runID为6 caseID为2的bug @SonarQube_Bug2,SonarQube_Bug12

- 测试获取runID为6 caseID为6的bug @SonarQube_Bug2,SonarQube_Bug12

- 测试获取runID为6 caseID为10的bug @SonarQube_Bug2,SonarQube_Bug12

- 测试获取runID为6 caseID不存在的bug @SonarQube_Bug2,SonarQube_Bug12

- 测试获取runID为10 caseID为0的bug @测试单转Bug3,测试单转Bug13

- 测试获取runID为10 caseID为2的bug @测试单转Bug3,测试单转Bug13

- 测试获取runID为10 caseID为6的bug @测试单转Bug3,测试单转Bug13

- 测试获取runID为10 caseID为10的bug @测试单转Bug3,测试单转Bug13

- 测试获取runID为10 caseID不存在的bug @测试单转Bug3,测试单转Bug13

- 测试获取runID为不存在 caseID为0的bug @0
- 测试获取runID为不存在 caseID为2的bug @0
- 测试获取runID为不存在 caseID为6的bug @0
- 测试获取runID为不存在 caseID为10的bug @0
- 测试获取runID为不存在 caseID不存在的bug @0

*/

$runIDList  = array('0', '2', '6', '10', '1000001');
$caseIDList = array('0', '2', '6', '10', '1000001');

$bug=new bugTest();
r($bug->getCaseBugsTest($runIDList[0], $caseIDList[1])) && p() && e('测试单转Bug1,测试单转Bug11');     // 测试获取runID为0 caseID为2的bug
r($bug->getCaseBugsTest($runIDList[0], $caseIDList[2])) && p() && e('SonarQube_Bug2,SonarQube_Bug12'); // 测试获取runID为0 caseID为6的bug
r($bug->getCaseBugsTest($runIDList[0], $caseIDList[3])) && p() && e('测试单转Bug3,测试单转Bug13');     // 测试获取runID为0 caseID为10的bug
r($bug->getCaseBugsTest($runIDList[0], $caseIDList[4])) && p() && e('0                         ');     // 测试获取runID为0 caseID不存在的bug
r($bug->getCaseBugsTest($runIDList[1], $caseIDList[0])) && p() && e('测试单转Bug1,测试单转Bug11');     // 测试获取runID为2 caseID为0的bug
r($bug->getCaseBugsTest($runIDList[1], $caseIDList[1])) && p() && e('测试单转Bug1,测试单转Bug11');     // 测试获取runID为2 caseID为2的bug
r($bug->getCaseBugsTest($runIDList[1], $caseIDList[2])) && p() && e('测试单转Bug1,测试单转Bug11');     // 测试获取runID为2 caseID为6的bug
r($bug->getCaseBugsTest($runIDList[1], $caseIDList[3])) && p() && e('测试单转Bug1,测试单转Bug11');     // 测试获取runID为2 caseID为10的bug
r($bug->getCaseBugsTest($runIDList[1], $caseIDList[4])) && p() && e('测试单转Bug1,测试单转Bug11');     // 测试获取runID为2 caseID不存在的bug
r($bug->getCaseBugsTest($runIDList[2], $caseIDList[0])) && p() && e('SonarQube_Bug2,SonarQube_Bug12'); // 测试获取runID为6 caseID为0的bug
r($bug->getCaseBugsTest($runIDList[2], $caseIDList[1])) && p() && e('SonarQube_Bug2,SonarQube_Bug12'); // 测试获取runID为6 caseID为2的bug
r($bug->getCaseBugsTest($runIDList[2], $caseIDList[2])) && p() && e('SonarQube_Bug2,SonarQube_Bug12'); // 测试获取runID为6 caseID为6的bug
r($bug->getCaseBugsTest($runIDList[2], $caseIDList[3])) && p() && e('SonarQube_Bug2,SonarQube_Bug12'); // 测试获取runID为6 caseID为10的bug
r($bug->getCaseBugsTest($runIDList[2], $caseIDList[4])) && p() && e('SonarQube_Bug2,SonarQube_Bug12'); // 测试获取runID为6 caseID不存在的bug
r($bug->getCaseBugsTest($runIDList[3], $caseIDList[0])) && p() && e('测试单转Bug3,测试单转Bug13');     // 测试获取runID为10 caseID为0的bug
r($bug->getCaseBugsTest($runIDList[3], $caseIDList[1])) && p() && e('测试单转Bug3,测试单转Bug13');     // 测试获取runID为10 caseID为2的bug
r($bug->getCaseBugsTest($runIDList[3], $caseIDList[2])) && p() && e('测试单转Bug3,测试单转Bug13');     // 测试获取runID为10 caseID为6的bug
r($bug->getCaseBugsTest($runIDList[3], $caseIDList[3])) && p() && e('测试单转Bug3,测试单转Bug13');     // 测试获取runID为10 caseID为10的bug
r($bug->getCaseBugsTest($runIDList[3], $caseIDList[4])) && p() && e('测试单转Bug3,测试单转Bug13');     // 测试获取runID为10 caseID不存在的bug
r($bug->getCaseBugsTest($runIDList[4], $caseIDList[0])) && p() && e('0');                              // 测试获取runID为不存在 caseID为0的bug
r($bug->getCaseBugsTest($runIDList[4], $caseIDList[1])) && p() && e('0');                              // 测试获取runID为不存在 caseID为2的bug
r($bug->getCaseBugsTest($runIDList[4], $caseIDList[2])) && p() && e('0');                              // 测试获取runID为不存在 caseID为6的bug
r($bug->getCaseBugsTest($runIDList[4], $caseIDList[3])) && p() && e('0');                              // 测试获取runID为不存在 caseID为10的bug
r($bug->getCaseBugsTest($runIDList[4], $caseIDList[4])) && p() && e('0');                              // 测试获取runID为不存在 caseID不存在的bug
