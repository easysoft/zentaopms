#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';
su('admin');

/**

title=bugModel->getDataOfResolvedBugsPerDay();
timeout=0
cid=1

- 获取每天解决的 bug第0条的value属性 @1

*/

$bug = new bugTest();
r($bug->getDataOfResolvedBugsPerDayTest()) && p('0:value') && e('1'); // 获取每天解决的 bug