#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php'; su('admin');
include dirname(__FILE__, 2) . '/bug.class.php';

/**

title=bugModel->getBySonarqubeID();
cid=1
pid=1

获取sonarqubeID为0的bug issueKey数量 >> 0
获取sonarqubeID为2的bug issueKey数量 >> 1

*/

$sonarqubeIDList = array('0', '2');

$bug=new bugTest();

r($bug->getBySonarqubeIDTest($sonarqubeIDList[0])) && p() && e('0'); //获取sonarqubeID为0的bug issueKey数量
r($bug->getBySonarqubeIDTest($sonarqubeIDList[1])) && p() && e('1'); //获取sonarqubeID为2的bug issueKey数量