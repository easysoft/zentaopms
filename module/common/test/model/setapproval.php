#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('company')->gen(1);

/**

title=测试 commonModel::setApproval();
timeout=0
cid=1

- 查看是否开启了审批流程 @0

*/

global $tester, $config;
$tester->loadModel('common')->setApproval();

r($config->openedApproval) && p('') && e('0'); // 查看是否开启了审批流程