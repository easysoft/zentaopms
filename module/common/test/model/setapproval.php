#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('company')->gen(1);

/**

title=测试 commonModel::setApproval();
timeout=0
cid=15709

- 查看开源版是否开启了审批流程 @0
- 查看企业版是否开启了审批流程 @1
- 查看旗舰版是否开启了审批流程 @1
- 查看IPD版本是否开启了审批流程 @1
- 查看OR界面下的IPD版本是否开启了审批流程 @1
- 查看运营界面下的IPD版本是否开启了审批流程 @0

*/

global $tester, $config;
$config->edition = 'open';
$tester->loadModel('common')->setApproval();
r($config->openedApproval) && p('') && e('0'); // 查看开源版是否开启了审批流程

$config->edition = 'biz';
$tester->loadModel('common')->setApproval();
r($config->openedApproval) && p('') && e('1'); // 查看企业版是否开启了审批流程

$config->edition = 'max';
$tester->loadModel('common')->setApproval();
r($config->openedApproval) && p('') && e('1'); // 查看旗舰版是否开启了审批流程

$config->edition = 'ipd';
$tester->loadModel('common')->setApproval();
r($config->openedApproval) && p('') && e('1'); // 查看IPD版本是否开启了审批流程

$config->vision  = 'or';
$config->edition = 'ipd';
$tester->loadModel('common')->setApproval();
r($config->openedApproval) && p('') && e('1'); // 查看OR界面下的IPD版本是否开启了审批流程

$config->vision  = 'lite';
$config->edition = 'ipd';
$tester->loadModel('common')->setApproval();
r($config->openedApproval) && p('') && e('0'); // 查看运营界面下的IPD版本是否开启了审批流程
