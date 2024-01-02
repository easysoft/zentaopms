#!/usr/bin/env php
<?php

/**

title=测试 cneModel->getDefaultAccount();
timeout=0
cid=1

- 不传递组件名属性username @admin
- 传递错误的组件名属性username @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

zdTable('space')->config('space')->gen(2);
zdTable('solution')->config('solution')->gen(1);
zdTable('instance')->config('instance')->gen(2, true, false);

$cneModel  = new cneTest();
$component = '';

r($cneModel->getDefaultAccountTest($component)) && p('username') && e('admin'); // 不传递组件名

$component = 'username';
r($cneModel->getDefaultAccountTest($component)) && p('username') && e('0'); // 传递错误的组件名