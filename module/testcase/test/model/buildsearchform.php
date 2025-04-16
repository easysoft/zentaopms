#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('product')->gen('45');
zenData('branch')->gen('10');
zenData('project')->gen('30');
zenData('story')->gen('10');
zenData('module')->gen('10');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->buildSearchForm();
cid=1
pid=1

*/