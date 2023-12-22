#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('company')->gen(1);

/**

title=测试 commonModel::setCompany();
timeout=0
cid=1

- 查看设置的公司名称 @易软天创网络科技有限公司
- 查看设置的公司ID @1

*/

global $tester, $app;
$tester->loadModel('common')->setCompany();

r($app->company->name) && p('') && e('易软天创网络科技有限公司'); // 查看设置的公司名称
r($app->company->id)   && p('') && e('1'); // 查看设置的公司ID