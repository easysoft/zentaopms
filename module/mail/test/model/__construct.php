#!/usr/bin/env php
<?php

/**

title=测试 mailModel::__construct();
timeout=0
cid=0

- 执行mailTest模块的__constructTest方法 属性isMailModel @1
- 执行mailTest模块的__constructTest方法 属性hasMTA @1
- 执行mailTest模块的__constructTest方法 属性hasErrors @1
- 执行mailTest模块的__constructTest方法 属性hasConfig @1
- 执行mailTest模块的__constructTest方法 属性mtaType @PHPMailer

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

su('admin');

$mailTest = new mailTest();

r($mailTest->__constructTest()) && p('isMailModel') && e('1');
r($mailTest->__constructTest()) && p('hasMTA') && e('1');
r($mailTest->__constructTest()) && p('hasErrors') && e('1');
r($mailTest->__constructTest()) && p('hasConfig') && e('1');
r($mailTest->__constructTest()) && p('mtaType') && e('PHPMailer');