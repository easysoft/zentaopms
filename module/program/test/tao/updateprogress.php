#!/usr/bin/env php
<?php
/**

title=测试 programTao::updateProgress();
timeout=0
cid=17722

- 获取系统中所有项目集的进度 1第1条的progress属性 @0.00
- 获取系统中所有项目集的进度 2第2条的progress属性 @0.00
- 获取系统中所有项目集的进度 3第3条的progress属性 @0.00
- 获取系统中所有项目集的进度 4第4条的progress属性 @0.00
- 获取系统中所有项目集的进度 5第5条的progress属性 @0.0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('project')->loadYaml('program')->gen(20);
zenData('user')->gen(5);
su('admin');

$programTester = new programTaoTest();
$results = $programTester->updateProgressTest();
r($results) && p('1:progress') && e('0.00'); // 获取系统中所有项目集的进度 1
r($results) && p('2:progress') && e('0.00'); // 获取系统中所有项目集的进度 2
r($results) && p('3:progress') && e('0.00'); // 获取系统中所有项目集的进度 3
r($results) && p('4:progress') && e('0.00'); // 获取系统中所有项目集的进度 4
r($results) && p('5:progress') && e('0.00'); // 获取系统中所有项目集的进度 5
