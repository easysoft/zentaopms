#!/usr/bin/env php
<?php

/**

title=测试 mailModel::getConfigByMXRR();
timeout=0
cid=17007

- 执行mail模块的getConfigByMXRRTest方法，参数是'qq.com', 'test' 属性host @smtp.qq.com
- 执行mail模块的getConfigByMXRRTest方法，参数是'263.net', 'test' 属性host @smtp.263.net
- 执行mail模块的getConfigByMXRRTest方法，参数是'nonexistent.example', 'test'  @0
- 执行mail模块的getConfigByMXRRTest方法，参数是'', 'test'  @0
- 执行mail模块的getConfigByMXRRTest方法，参数是'invalid-domain.test', 'test'  @0
- 执行mail模块的getConfigByMXRRTest方法，参数是'qq.com', 'testuser' 属性username @testuser@qq.com

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

su('admin');

$mail = new mailTest();

r($mail->getConfigByMXRRTest('qq.com', 'test')) && p('host') && e('smtp.qq.com');
r($mail->getConfigByMXRRTest('263.net', 'test')) && p('host') && e('smtp.263.net');
r($mail->getConfigByMXRRTest('nonexistent.example', 'test')) && p() && e('0');
r($mail->getConfigByMXRRTest('', 'test')) && p() && e('0');
r($mail->getConfigByMXRRTest('invalid-domain.test', 'test')) && p() && e('0');
r($mail->getConfigByMXRRTest('qq.com', 'testuser')) && p('username') && e('testuser@qq.com');