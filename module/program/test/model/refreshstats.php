#!/usr/bin/env php
<?php

/**

title=测试 programTao::refreshStats();
timeout=0
cid=17708

- 更新系统中项目、项目集的统计信息第1条的progress属性 @22.80
- 更新系统中项目、项目集的统计信息第2条的progress属性 @28.00
- 更新系统中项目、项目集的统计信息第3条的progress属性 @0.00
- 更新系统中项目、项目集的统计信息第4条的progress属性 @0.00
- 更新系统中项目、项目集的统计信息第5条的progress属性 @0.00

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('program')->gen(20);
zenData('task')->loadYaml('task')->gen(20);
zenData('team')->loadYaml('team')->gen(30);
zenData('user')->gen(5);
su('admin');

$programTester = new programModelTest();
$result = $programTester->refreshStatsTest();
r($result) && p('1:progress') && e('22.80'); // 更新系统中项目、项目集的统计信息
r($result) && p('2:progress') && e('28.00'); // 更新系统中项目、项目集的统计信息
r($result) && p('3:progress') && e('0.00');  // 更新系统中项目、项目集的统计信息
r($result) && p('4:progress') && e('0.00');  // 更新系统中项目、项目集的统计信息
r($result) && p('5:progress') && e('0.00');  // 更新系统中项目、项目集的统计信息