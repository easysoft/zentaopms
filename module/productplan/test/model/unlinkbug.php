#!/usr/bin/env php
<?php

/**

title=productpanModel->unlinkBug();
timeout=0
cid=17648

- 正确的bug属性title @BUG1
- 正确的bug属性title @BUG2
- 正确的bug属性title @BUG3
- 空的bug @0
- 错误的bug @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplan.unittest.class.php';

zenData('bug')->gen(10);

$tester = new productPlan();

r($tester->unlinkBugTest(1))  && p('title') && e('BUG1'); // 正确的bug
r($tester->unlinkBugTest(2))  && p('title') && e('BUG2'); // 正确的bug
r($tester->unlinkBugTest(3))  && p('title') && e('BUG3'); // 正确的bug
r($tester->unlinkBugTest(0))  && p()        && e('0');    // 空的bug
r($tester->unlinkBugTest(20)) && p()        && e('0');    // 错误的bug
