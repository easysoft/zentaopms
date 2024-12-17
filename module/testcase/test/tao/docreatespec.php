#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('user')->gen('1');
zenData('case')->gen('0');

su('admin');

/**

title=测试 testcaseModel->doCreateSpec();
cid=1
pid=1

*/

$caseID = array(1, 2, 3, 4, 5);
$files  = array('', array(), '1,2', array(1, 2));
