#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('project')->loadYaml('execution')->gen(2);
zenData('task')->loadYaml('task')->gen(50);

/**

title=测试 metricModel->getResultByCodeFromData();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('metric');
