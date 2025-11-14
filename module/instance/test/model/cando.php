#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('instance')->gen(2);

/**

title=instanceModel->getByID();
timeout=0
cid=16782

- 查看第一条instance是否可操作start @0
- 查看第一条instance是否可操作stop @1
- 查看第一条instance是否可操作uninstall @1
- 查看第一条instance是否可操作visit @1
- 查看第二条instance是否可操作start @0
- 查看第二条instance是否可操作stop @1
- 查看第二条instance是否可操作uninstall @1
- 查看第二条instance是否可操作visit @1

*/

global $tester;
$tester->loadModel('instance');

$instance = $tester->instance->getByID(1);
r($tester->instance->canDo('start', $instance))     && p('') && e('0'); // 查看第一条instance是否可操作start
r($tester->instance->canDo('stop', $instance))      && p('') && e('1'); // 查看第一条instance是否可操作stop
r($tester->instance->canDo('uninstall', $instance)) && p('') && e('1'); // 查看第一条instance是否可操作uninstall
r($tester->instance->canDo('visit', $instance))     && p('') && e('1'); // 查看第一条instance是否可操作visit

$instance = $tester->instance->getByID(2);
r($tester->instance->canDo('start', $instance))     && p('') && e('0'); // 查看第二条instance是否可操作start
r($tester->instance->canDo('stop', $instance))      && p('') && e('1'); // 查看第二条instance是否可操作stop
r($tester->instance->canDo('uninstall', $instance)) && p('') && e('1'); // 查看第二条instance是否可操作uninstall
r($tester->instance->canDo('visit', $instance))     && p('') && e('1'); // 查看第二条instance是否可操作visit
