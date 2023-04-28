#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

global $tester;
$tester->loadModel('project');

r($oriject->getBudgetWithUnit(99)) && p() && e('99');
