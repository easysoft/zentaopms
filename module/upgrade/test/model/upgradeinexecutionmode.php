#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->upgradeInExecutionMode();
timeout=0
cid=19561

- 测试生成的项目
 - 第131条的name属性 @2020
 - 第131条的status属性 @doing
 - 第131条的begin属性 @2020-01-01
 - 第131条的end属性 @2022-12-31
 - 第131条的days属性 @783
 - 第132条的name属性 @2021
 - 第132条的status属性 @doing
 - 第132条的begin属性 @2021-01-01
 - 第132条的end属性 @2021-12-31
 - 第132条的days属性 @260
 - 第133条的name属性 @2022
 - 第133条的status属性 @doing
 - 第133条的begin属性 @2022-01-01
 - 第133条的end属性 @2022-05-31
 - 第133条的days属性 @106

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

zenData('project')->loadYaml('execution')->gen(30);
zenData('user')->gen(5);
su('admin');

$upgrade = new upgradeTest();
r($upgrade->upgradeInExecutionModeTest(0)) && p('131:name,status,begin,end,days;132:name,status,begin,end,days;133:name,status,begin,end,days') && e('2020,doing,2020-01-01,2022-12-31,783;2021,doing,2021-01-01,2021-12-31,260;2022,doing,2022-01-01,2022-05-31,106'); //测试生成的项目