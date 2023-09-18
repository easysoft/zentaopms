#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';

zdTable('project')->gen(20);
su('admin');

/**
title=测试programTao::getNormalActions();
timeout=0
cid=1

*/

$tester = new programTest();

r($tester->getNormalActionsTest(1))  && p('2:name') && e('delete'); // 项目集
r($tester->getNormalActionsTest(11)) && p('0:name') && e('edit');   // 项目
