#!/usr/bin/env php
<?php

/**

title=测试 cneModel->getDomain();
timeout=0
cid=1

- 不传递组件名属性access_host @rheu.dops.corp.cc
- 传递错误的组件名属性access_host @0
- 传递正确的组件名属性access_host @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

zdTable('space')->config('space')->gen(2);
zdTable('solution')->config('solution')->gen(1);
zdTable('instance')->config('instance')->gen(2, true, false);

$cneModel  = new cneTest();
$component = '';
r($cneModel->getDomainTest($component)) && p('access_host') && e('rheu.dops.corp.cc'); // 不传递组件名

$component = 'username';
r($cneModel->getDomainTest($component)) && p('access_host') && e('0'); // 传递错误的组件名

$component = 'minio';
r($cneModel->getDomainTest($component)) && p('access_host') && e('0'); // 传递正确的组件名