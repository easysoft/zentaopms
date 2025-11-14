#!/usr/bin/env php
<?php

/**

title=测试 userZen::responseForLoginFail();
timeout=0
cid=19682

- 执行userZenTest模块的responseForLoginFailTest方法，参数是'json', '' 属性status @failed
- 执行userZenTest模块的responseForLoginFailTest方法，参数是'', '' 属性result @fail
- 执行userZenTest模块的responseForLoginFailTest方法，参数是'', 'testuser1' 属性result @fail
- 执行userZenTest模块的responseForLoginFailTest方法，参数是'', 'testuser2' 属性result @fail
- 执行userZenTest模块的responseForLoginFailTest方法，参数是'', 'testuser3' 属性result @fail

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$userZenTest = new userZenTest();

r($userZenTest->responseForLoginFailTest('json', '')) && p('status') && e('failed');
r($userZenTest->responseForLoginFailTest('', '')) && p('result') && e('fail');
r($userZenTest->responseForLoginFailTest('', 'testuser1')) && p('result') && e('fail');
r($userZenTest->responseForLoginFailTest('', 'testuser2')) && p('result') && e('fail');
r($userZenTest->responseForLoginFailTest('', 'testuser3')) && p('result') && e('fail');