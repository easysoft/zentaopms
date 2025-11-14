#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printLink();
timeout=0
cid=15696

- 执行commonTest模块的printLinkTest方法，参数是'misc', 'ping', '', 'Ping', '', '', true, false, null 属性result @1
- 执行commonTest模块的printLinkTest方法，参数是'admin', 'forbidden', '', 'Forbidden', '', '', true, false, null  @0
- 执行commonTest模块的printLinkTest方法，参数是'user', 'login', '', 'Login', '', '', true, false, null 属性result @1
- 执行commonTest模块的printLinkTest方法，参数是'task', 'view', 'taskID=123', 'View Task', '_blank', 'class="btn"', true, false, null 属性result @1
- 执行commonTest模块的printLinkTest方法，参数是'project', 'browse', '', 'Projects', '', 'id="project-link"', true, false, null 属性result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

$commonTest = new commonTest();

r($commonTest->printLinkTest('misc', 'ping', '', 'Ping', '', '', true, false, null)) && p('result') && e('1');
r($commonTest->printLinkTest('admin', 'forbidden', '', 'Forbidden', '', '', true, false, null)) && p() && e('0');
r($commonTest->printLinkTest('user', 'login', '', 'Login', '', '', true, false, null)) && p('result') && e('1');
r($commonTest->printLinkTest('task', 'view', 'taskID=123', 'View Task', '_blank', 'class="btn"', true, false, null)) && p('result') && e('1');
r($commonTest->printLinkTest('project', 'browse', '', 'Projects', '', 'id="project-link"', true, false, null)) && p('result') && e('1');