#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 upgradeModel->upgradeInProjectMode();
cid=1

- 测试将历史的项目作为项目升级 放在项目集 1 下 原有模式为 classic @111:1,0;112:1,0;113:1,0;114:1,0;115:1,0;116:1,0;117:1,0;118:1,0;119:1,0;120:1,0

- 测试将历史的项目作为项目升级 放在项目集 2 下 原有模式为 new @111:2,1;112:2,1;113:2,1;114:2,1;115:2,1;116:2,1;117:2,1;118:2,1;119:2,1;120:2,1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('project')->config('execution')->gen(10);
zdTable('doclib')->gen(30);
zdTable('doc')->gen(30);
zdTable('user')->gen(5);
su('admin');

$upgrade = new upgradeTest();

$programID = array(1, 2);
$fromMode  = array('classic', 'new');

r($upgrade->upgradeInProjectModeTest($programID[0], $fromMode[0])) && p() && e('111:1,0;112:1,0;113:1,0;114:1,0;115:1,0;116:1,0;117:1,0;118:1,0;119:1,0;120:1,0'); // 测试将历史的项目作为项目升级 放在项目集 1 下 原有模式为 classic

zdTable('project')->config('execution')->gen(10);

r($upgrade->upgradeInProjectModeTest($programID[1], $fromMode[1])) && p() && e('111:2,1;112:2,1;113:2,1;114:2,1;115:2,1;116:2,1;117:2,1;118:2,1;119:2,1;120:2,1'); // 测试将历史的项目作为项目升级 放在项目集 2 下 原有模式为 new
