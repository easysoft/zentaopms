#!/usr/bin/env php
<?php

/**

title=测试 mailModel::send();
timeout=0
cid=17016

- 执行mailTest模块的sendTest方法，参数是'admin', 'test subject', 'test body'  @0
- 执行mailTest模块的sendTest方法，参数是'admin', 'test subject', 'test body'  @1
- 执行mailTest模块的sendTest方法，参数是'admin', 'test subject', 'test body', '', false, array  @0
- 执行mailTest模块的sendTest方法，参数是'', 'test subject', 'test body'  @0
- 执行mailTest模块的sendTest方法，参数是'admin', 'test subject', 'test body' 属性nonexistent @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

su('admin');

$mailTest = new mailTest();

r($mailTest->sendTest('admin', 'test subject', 'test body')) && p() && e('0');
r($mailTest->sendTest('admin', 'test subject', 'test body')) && p() && e('1');
r($mailTest->sendTest('admin', 'test subject', 'test body', '', false, array(), true)) && p() && e('0');
r($mailTest->sendTest('', 'test subject', 'test body')) && p() && e('0');
r($mailTest->sendTest('admin', 'test subject', 'test body')) && p('nonexistent') && e('~~');