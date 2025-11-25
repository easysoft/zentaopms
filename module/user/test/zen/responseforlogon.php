#!/usr/bin/env php
<?php

/**

title=测试 userZen::responseForLogon();
timeout=0
cid=19683

- 执行userTest模块的responseForLogonTest方法，参数是'', 'json', '', '', '', '' 属性status @success
- 执行userTest模块的responseForLogonTest方法，参数是'', '', '', '', '', 'http://example.com/'
 - 属性result @success
 - 属性locate @http://example.com/
- 执行userTest模块的responseForLogonTest方法，参数是'http://example.com/user-login.html', '', 'user-login', '', '', 'http://example.com/'
 - 属性result @success
 - 属性locate @http://example.com/
- 执行userTest模块的responseForLogonTest方法，参数是'http://example.com/ajax-call', '', '', '', '', 'http://example.com/'
 - 属性result @success
 - 属性locate @http://example.com/
- 执行userTest模块的responseForLogonTest方法，参数是'http://example.com/task-view', '', '', '', 'http://example.com/task-view', 'http://example.com/'
 - 属性result @success
 - 属性locate @http://example.com/

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$userTest = new userZenTest();

r($userTest->responseForLogonTest('', 'json', '', '', '', '')) && p('status') && e('success');
r($userTest->responseForLogonTest('', '', '', '', '', 'http://example.com/')) && p('result,locate') && e('success,http://example.com/');
r($userTest->responseForLogonTest('http://example.com/user-login.html', '', 'user-login', '', '', 'http://example.com/')) && p('result,locate') && e('success,http://example.com/');
r($userTest->responseForLogonTest('http://example.com/ajax-call', '', '', '', '', 'http://example.com/')) && p('result,locate') && e('success,http://example.com/');
r($userTest->responseForLogonTest('http://example.com/task-view', '', '', '', 'http://example.com/task-view', 'http://example.com/')) && p('result,locate') && e('success,http://example.com/');
