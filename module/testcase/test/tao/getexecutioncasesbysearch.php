#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';
su('admin');

zenData('projectcase')->gen(10);
zenData('case')->gen(10);
$userquery = zenData('userquery');
$userquery->sql->range("(( 1   AND `title`  LIKE '%2%' ) AND ( 1  )) AND deleted = '0'");
$userquery->gen(10);

$executionID = array(101, 102, 103, 104, 105);
$productID   = array(0, 1);
$branchID    = array('all', 0);
$paramID     = array(0, 1);
$query       = array("`product` = 'all' and `branch` = 'all'", "`title` like '%1%'");
$orderBy     = array('id_desc', 'id_asc');

$testcase = new testcaseTest();
