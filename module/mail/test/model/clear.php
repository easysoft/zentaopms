#!/usr/bin/env php
<?php

/**

title=测试 mailModel::clear();
timeout=0
cid=0

- 执行mailTest模块的clearTest方法，参数是true, true 属性cleared @1
- 执行mailTest模块的clearTest方法，参数是true, false
 - 属性hasRecipients @1
 - 属性processed @1
- 执行mailTest模块的clearTest方法，参数是false, true
 - 属性hasAttachments @1
 - 属性processed @1
- 执行mailTest模块的clearTest方法，参数是false, false 属性processed @1
- 执行mailTest模块的clearTest方法，参数是true, true
 - 属性methodExecuted @1
 - 属性cleared @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

su('admin');

$mailTest = new mailTest();

r($mailTest->clearTest(true, true)) && p('cleared') && e('1');
r($mailTest->clearTest(true, false)) && p('hasRecipients,processed') && e('1,1');
r($mailTest->clearTest(false, true)) && p('hasAttachments,processed') && e('1,1');
r($mailTest->clearTest(false, false)) && p('processed') && e('1');
r($mailTest->clearTest(true, true)) && p('methodExecuted,cleared') && e('1,1');