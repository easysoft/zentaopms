#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('company')->gen(1);
zenData('user')->gen(5);

su('admin');

/**

title=测试 commonModel::getUserPriv();
timeout=0
cid=15678

- 执行commonTest模块的getUserPrivTest方法，参数是'user', 'browse', null, '', 'nouser'  @0
- 执行commonTest模块的getUserPrivTest方法，参数是'user', 'browse', null, '', 'admin'  @1
- 执行commonTest模块的getUserPrivTest方法，参数是'user', 'browse', null, '', 'openmethod'  @1
- 执行commonTest模块的getUserPrivTest方法，参数是'user', 'browse', null, '', 'hasrights'  @1
- 执行commonTest模块的getUserPrivTest方法，参数是'task', 'create', null, '', 'norights'  @0

*/

include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

$commonTest = new commonTest();

r($commonTest->getUserPrivTest('user', 'browse', null, '', 'nouser')) && p() && e('0');
r($commonTest->getUserPrivTest('user', 'browse', null, '', 'admin')) && p() && e('1');
r($commonTest->getUserPrivTest('user', 'browse', null, '', 'openmethod')) && p() && e('1');
r($commonTest->getUserPrivTest('user', 'browse', null, '', 'hasrights')) && p() && e('1');
r($commonTest->getUserPrivTest('task', 'create', null, '', 'norights')) && p() && e('0');