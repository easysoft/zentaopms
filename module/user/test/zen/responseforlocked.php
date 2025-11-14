#!/usr/bin/env php
<?php

/**

title=测试 userZen::responseForLocked();
timeout=0
cid=19681

- 执行userTest模块的responseForLockedTest方法，参数是'' 属性result @fail
- 执行userTest模块的responseForLockedTest方法，参数是'json' 属性status @failed
- 执行userTest模块的responseForLockedTest方法，参数是'web' 属性result @fail
- 执行userTest模块的responseForLockedTest方法，参数是'invalid' 属性result @fail
- 执行userTest模块的responseForLockedTest方法，参数是' '
 - 属性result @fail
 - 属性message @密码尝试次数太多，请联系管理员解锁，或10分钟后重试。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$userTest = new userZenTest();

r($userTest->responseForLockedTest('')) && p('result') && e('fail');
r($userTest->responseForLockedTest('json')) && p('status') && e('failed');
r($userTest->responseForLockedTest('web')) && p('result') && e('fail');
r($userTest->responseForLockedTest('invalid')) && p('result') && e('fail');
r($userTest->responseForLockedTest(' ')) && p('result,message') && e('fail,密码尝试次数太多，请联系管理员解锁，或10分钟后重试。');