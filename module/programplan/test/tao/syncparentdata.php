#!/usr/bin/env php
<?php
/**

title=测试 loadModel->syncParentData()
cid=0

*/
include dirname(__FILE__, 5). '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

zenData('project')->loadYaml('project')->gen(8)->fixPath();
zenData('task')->loadYaml('task')->gen(10);
zenData('projectstory')->loadYaml('projectstory')->gen(10);
zenData('bug')->loadYaml('bug')->gen(10);
zenData('case')->loadYaml('case')->gen(10);
zenData('projectcase')->loadYaml('projectcase')->gen(10);
zenData('testtask')->loadYaml('testtask')->gen(10);
zenData('testreport')->loadYaml('testreport')->gen(10);
zenData('build')->loadYaml('build')->gen(10);
zenData('effort')->loadYaml('effort')->gen(10);
zenData('action')->loadYaml('action')->gen(10);
zenData('actionrecent')->loadYaml('actionrecent')->gen(10);
zenData('doclib')->loadYaml('doclib')->gen(10);
zenData('doc')->loadYaml('doc')->gen(10);
zenData('module')->loadYaml('module')->gen(10);
zenData('team')->loadYaml('team')->gen(5);
