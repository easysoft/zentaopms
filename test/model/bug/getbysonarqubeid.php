#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

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