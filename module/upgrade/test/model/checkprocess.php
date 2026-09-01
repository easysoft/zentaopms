#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->checkProcess();
cid=19502

- 测试版本 18.1 管理模式 new 的流程 @0
- 测试版本 18.1 管理模式 classic 的流程 @0
- 测试版本 pro10.1 管理模式 new 的流程 @0
- 测试版本 pro10.1 管理模式 classic 的流程 @0
- 测试版本 pro3.1 管理模式 new 的流程 @updateFile:process;search:notice
- 测试版本 pro3.1 管理模式 classic 的流程 @updateFile:process
- 测试版本 biz4.1 管理模式 new 的流程 @search:notice
- 测试版本 biz4.1 管理模式 classic 的流程 @0
- 测试版本 biz6.1 管理模式 new 的流程 @0
- 测试版本 biz6.1 管理模式 classic 的流程 @0
- 测试版本 max4.3 管理模式 new 的流程 @0
- 测试版本 max4.3 管理模式 classic 的流程 @0
- 测试版本 ipd1.1 管理模式 new 的流程 @0
- 测试版本 ipd1.1 管理模式 classic 的流程 @0
- 测试版本 18.1 管理模式 new 且存在MyISAM表的流程 @changeEngine:notice

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->loadYaml('user')->gen(5);

su('admin');

$upgrade = new upgradeModelTest();

$emptyErrors = array();

$versions   = array('18.1', 'pro10.1', 'pro3.1', 'biz4.1', 'biz6.1', 'max4.3', 'ipd1.1');
$systemMode = array('new', 'classic');

r($upgrade->checkProcessTest($versions[0], $systemMode[0])) && p() && e('0');                                                    // 测试版本 18.1 管理模式 new 的流程
r($upgrade->checkProcessTest($versions[0], $systemMode[1])) && p() && e('0');                                                    // 测试版本 18.1 管理模式 classic 的流程
r($upgrade->checkProcessTest($versions[1], $systemMode[0])) && p() && e('0');                                                    // 测试版本 pro10.1 管理模式 new 的流程
r($upgrade->checkProcessTest($versions[1], $systemMode[1])) && p() && e('0');                                                    // 测试版本 pro10.1 管理模式 classic 的流程
r($upgrade->checkProcessTest($versions[2], $systemMode[0])) && p() && e('updateFile:process;search:notice');                     // 测试版本 pro3.1 管理模式 new 的流程
r($upgrade->checkProcessTest($versions[2], $systemMode[1])) && p() && e('updateFile:process');                                   // 测试版本 pro3.1 管理模式 classic 的流程
r($upgrade->checkProcessTest($versions[3], $systemMode[0])) && p() && e('search:notice');                                        // 测试版本 biz4.1 管理模式 new 的流程
r($upgrade->checkProcessTest($versions[3], $systemMode[1])) && p() && e('0');                                                    // 测试版本 biz4.1 管理模式 classic 的流程
r($upgrade->checkProcessTest($versions[4], $systemMode[0])) && p() && e('0');                                                    // 测试版本 biz6.1 管理模式 new 的流程
r($upgrade->checkProcessTest($versions[4], $systemMode[1])) && p() && e('0');                                                    // 测试版本 biz6.1 管理模式 classic 的流程
r($upgrade->checkProcessTest($versions[5], $systemMode[0])) && p() && e('0');                                                    // 测试版本 max4.3 管理模式 new 的流程
r($upgrade->checkProcessTest($versions[5], $systemMode[1])) && p() && e('0');                                                    // 测试版本 max4.3 管理模式 classic 的流程
r($upgrade->checkProcessTest($versions[6], $systemMode[0])) && p() && e('0');                                                    // 测试版本 ipd1.1 管理模式 new 的流程
r($upgrade->checkProcessTest($versions[6], $systemMode[1])) && p() && e('0');                                                    // 测试版本 ipd1.1 管理模式 classic 的流程

/* 数据库中存在 MyISAM 表时输出 changeEngine 提示。*/
$myisamTable = $tester->config->db->prefix . 'upgrade_test_myisam';
if($tester->config->db->driver == 'mysql')
{
    $upgrade->instance->dbh->exec("CREATE TABLE `{$myisamTable}` (`id` int unsigned NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) ENGINE=MyISAM");
    r($upgrade->checkProcessTest($versions[0], $systemMode[0])) && p() && e('changeEngine:notice'); // 测试版本 18.1 管理模式 new 且存在MyISAM表的流程
    $upgrade->instance->dbh->exec("DROP TABLE `{$myisamTable}`");
}
