#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->checkProcess();
cid=1

- 测试版本 18.1 管理模式 new 的流程 @0
- 测试版本 18.1 管理模式 classic 的流程 @0
- 测试版本 pro10.1 管理模式 new 的流程 @changeEngine:notice
- 测试版本 pro10.1 管理模式 classic 的流程 @changeEngine:notice
- 测试版本 pro3.1 管理模式 new 的流程 @updateFile:process;search:notice;changeEngine:notice
- 测试版本 pro3.1 管理模式 classic 的流程 @updateFile:process;changeEngine:notice
- 测试版本 biz4.1 管理模式 new 的流程 @search:notice;changeEngine:notice
- 测试版本 biz4.1 管理模式 classic 的流程 @changeEngine:notice
- 测试版本 biz6.1 管理模式 new 的流程 @changeEngine:notice
- 测试版本 biz6.1 管理模式 classic 的流程 @changeEngine:notice
- 测试版本 max4.3 管理模式 new 的流程 @0
- 测试版本 max4.3 管理模式 classic 的流程 @0
- 测试版本 ipd1.1 管理模式 new 的流程 @0
- 测试版本 ipd1.1 管理模式 classic 的流程 @0

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->config('user')->gen(5);

su('admin');

$upgrade = new upgradeTest();

$emptyErrors = array();

$versions   = array('18.1', 'pro10.1', 'pro3.1', 'biz4.1', 'biz6.1', 'max4.3', 'ipd1.1');
$systemMode = array('new', 'classic');

r($upgrade->checkProcessTest($versions[0], $systemMode[0])) && p() && e('0');                                                    // 测试版本 18.1 管理模式 new 的流程
r($upgrade->checkProcessTest($versions[0], $systemMode[1])) && p() && e('0');                                                    // 测试版本 18.1 管理模式 classic 的流程
r($upgrade->checkProcessTest($versions[1], $systemMode[0])) && p() && e('changeEngine:notice');                                  // 测试版本 pro10.1 管理模式 new 的流程
r($upgrade->checkProcessTest($versions[1], $systemMode[1])) && p() && e('changeEngine:notice');                                  // 测试版本 pro10.1 管理模式 classic 的流程
r($upgrade->checkProcessTest($versions[2], $systemMode[0])) && p() && e('updateFile:process;search:notice;changeEngine:notice'); // 测试版本 pro3.1 管理模式 new 的流程
r($upgrade->checkProcessTest($versions[2], $systemMode[1])) && p() && e('updateFile:process;changeEngine:notice');               // 测试版本 pro3.1 管理模式 classic 的流程
r($upgrade->checkProcessTest($versions[3], $systemMode[0])) && p() && e('search:notice;changeEngine:notice');                    // 测试版本 biz4.1 管理模式 new 的流程
r($upgrade->checkProcessTest($versions[3], $systemMode[1])) && p() && e('changeEngine:notice');                                  // 测试版本 biz4.1 管理模式 classic 的流程
r($upgrade->checkProcessTest($versions[4], $systemMode[0])) && p() && e('changeEngine:notice');                                  // 测试版本 biz6.1 管理模式 new 的流程
r($upgrade->checkProcessTest($versions[4], $systemMode[1])) && p() && e('changeEngine:notice');                                  // 测试版本 biz6.1 管理模式 classic 的流程
r($upgrade->checkProcessTest($versions[5], $systemMode[0])) && p() && e('0');                                                    // 测试版本 max4.3 管理模式 new 的流程
r($upgrade->checkProcessTest($versions[5], $systemMode[1])) && p() && e('0');                                                    // 测试版本 max4.3 管理模式 classic 的流程
r($upgrade->checkProcessTest($versions[6], $systemMode[0])) && p() && e('0');                                                    // 测试版本 ipd1.1 管理模式 new 的流程
r($upgrade->checkProcessTest($versions[6], $systemMode[1])) && p() && e('0');                                                    // 测试版本 ipd1.1 管理模式 classic 的流程
