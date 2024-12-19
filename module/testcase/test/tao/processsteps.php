#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('user')->gen('1');
zenData('case')->gen('5');
zenData('casestep')->loadYaml('casestep')->gen('20');

su('admin');

/**

title=测试 testcaseModel->getSteps();
cid=1
pid=1

*/
