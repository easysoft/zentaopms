#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('bug')->loadYaml('bug_getbysonarqubeid')->gen(10);

/**

title=bugModel->getBySonarqubeID();
timeout=0
cid=15362

- 获取sonarqubeID为0的bug issueKey数量 @0
- 获取sonarqubeID为1的bug issueKey数量 @3
- 获取sonarqubeID为2的bug issueKey数量 @3
- 获取sonarqubeID为3的bug issueKey数量 @2
- 获取sonarqubeID为4的bug issueKey数量 @2
- 获取sonarqubeID为5的bug issueKey数量 @0

*/

$sonarqubeIDList = array(0, 1, 2, 3, 4, 5);

$bug=new bugTest();

r($bug->getBySonarqubeIDTest($sonarqubeIDList[0])) && p() && e('0'); //获取sonarqubeID为0的bug issueKey数量
r($bug->getBySonarqubeIDTest($sonarqubeIDList[1])) && p() && e('3'); //获取sonarqubeID为1的bug issueKey数量
r($bug->getBySonarqubeIDTest($sonarqubeIDList[2])) && p() && e('3'); //获取sonarqubeID为2的bug issueKey数量
r($bug->getBySonarqubeIDTest($sonarqubeIDList[3])) && p() && e('2'); //获取sonarqubeID为3的bug issueKey数量
r($bug->getBySonarqubeIDTest($sonarqubeIDList[4])) && p() && e('2'); //获取sonarqubeID为4的bug issueKey数量
r($bug->getBySonarqubeIDTest($sonarqubeIDList[5])) && p() && e('0'); //获取sonarqubeID为5的bug issueKey数量