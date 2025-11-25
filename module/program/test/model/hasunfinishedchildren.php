#!/usr/bin/env php
<?php

/**

title=测试 programModel::hasUnfinished();
timeout=0
cid=17704

- 获取项目集1下未完成的项目和项目集数量 @1
- 获取项目集2下未完成的项目和项目集数量 @1
- 获取项目集3下未完成的项目和项目集数量 @0
- 获取项目集5下未完成的项目和项目集数量 @0
- 获取项目集9下未完成的项目和项目集数量 @0
- 获取项目集11下未完成的项目和项目集数量 @0
- 获取项目集60下未完成的项目和项目集数量 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';
zenData('user')->gen(5);
zenData('project')->loadYaml('program')->gen(20)->fixpath();
su('admin');

$programTester = new programTest();
r($programTester->hasUnfinishedChildrenTest(1))  && p() && e('1'); // 获取项目集1下未完成的项目和项目集数量
r($programTester->hasUnfinishedChildrenTest(2))  && p() && e('1'); // 获取项目集2下未完成的项目和项目集数量
r($programTester->hasUnfinishedChildrenTest(3))  && p() && e('0'); // 获取项目集3下未完成的项目和项目集数量
r($programTester->hasUnfinishedChildrenTest(5))  && p() && e('0'); // 获取项目集5下未完成的项目和项目集数量
r($programTester->hasUnfinishedChildrenTest(9))  && p() && e('0'); // 获取项目集9下未完成的项目和项目集数量
r($programTester->hasUnfinishedChildrenTest(11)) && p() && e('0'); // 获取项目集11下未完成的项目和项目集数量
r($programTester->hasUnfinishedChildrenTest(60)) && p() && e('0'); // 获取项目集60下未完成的项目和项目集数量
