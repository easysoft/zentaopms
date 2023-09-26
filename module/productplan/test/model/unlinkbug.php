#!/usr/bin/env php
<?php
/**

title=productpanModel->unlinkBug();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';


zdTable('bug')->gen(10);

$tester = new productPlan();

r($tester->unlinkBugTest(1))  && p('title') && e('BUG1'); // 正确的bug
r($tester->unlinkBugTest(0))  && p()        && e('0');    // 空的bug
r($tester->unlinkBugTest(20)) && p()        && e('0');    // 错误的bug
