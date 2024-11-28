#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
/**

title=测试 projectModel->hasStageData();
timeout=0
cid=1

*/

zenData('project')->loadYaml('project')->gen(14)->fixPath();
zenData('task')->loadYaml('task')->gen(2);
zenData('effort')->loadYaml('effort')->gen(2);
zenData('bug')->loadYaml('bug')->gen(2);
zenData('story')->loadYaml('story')->gen(2);
zenData('projectstory')->loadYaml('projectstory')->gen(2);
zenData('case')->loadYaml('case')->gen(2);
zenData('projectcase')->loadYaml('projectcase')->gen(2);
zenData('build')->loadYaml('build')->gen(2);
zenData('testtask')->loadYaml('testtask')->gen(2);
zenData('testreport')->loadYaml('testreport')->gen(2);
zenData('doclib')->loadYaml('doclib')->gen(5);
zenData('doc')->loadYaml('doc')->gen(2);
zenData('module')->loadYaml('module')->gen(6);

$executionIdList = array(0) + range(1, 14);
