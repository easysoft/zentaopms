#!/usr/bin/env php
<?php
/**
title=测试programTao::getBaseDataList();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';

zdTable('team')->gen(5);
zdTable('project')->config('program')->gen(40);
su('admin');

$tester = new programTest();
$programList = $tester->program->getBaseDataList(array(3,13,23,33,44));

r($programList) && p('3:path', ';') && e(',3,,,');
r(isset($programList[44])) && p('') && e('0');
