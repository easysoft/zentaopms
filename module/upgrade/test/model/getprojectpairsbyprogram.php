#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getProjectPairsByProgram();
cid=1

- 测试获取项目集 0 的项目键值对 @0
- 测试获取项目集 1 的项目键值对 @11:项目11
- 测试获取项目集 2 的项目键值对 @12:项目12
- 测试获取项目集 3 的项目键值对 @13:项目13
- 测试获取项目集 10001 的项目键值对 @0

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->config('user')->gen(5);
zdTable('project')->gen(20);

su('admin');

$upgrade = new upgradeTest();

$programID = array(0, 1, 2, 3, 10001);

r($upgrade->getProjectPairsByProgramTest($programID[0])) && p() && e('0');         // 测试获取项目集 0 的项目键值对
r($upgrade->getProjectPairsByProgramTest($programID[1])) && p() && e('11:项目11'); // 测试获取项目集 1 的项目键值对
r($upgrade->getProjectPairsByProgramTest($programID[2])) && p() && e('12:项目12'); // 测试获取项目集 2 的项目键值对
r($upgrade->getProjectPairsByProgramTest($programID[3])) && p() && e('13:项目13'); // 测试获取项目集 3 的项目键值对
r($upgrade->getProjectPairsByProgramTest($programID[4])) && p() && e('0');         // 测试获取项目集 10001 的项目键值对
