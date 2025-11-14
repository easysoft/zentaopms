#!/usr/bin/env php
<?php

/**

title=- 单个ID @
timeout=0
cid=14963

- 执行actionTest模块的processCreateRequirementsActionExtraTest方法 属性extra @#1 用户需求1
- 执行actionTest模块的processCreateRequirementsActionExtraTest方法
 - 属性extra @#1 用户需求1
- 执行actionTest模块的processCreateRequirementsActionExtraTest方法 属性extra @0
- 执行actionTest模块的processCreateRequirementsActionExtraTest方法 属性extra @0
- 执行actionTest模块的processCreateRequirementsActionExtraTest方法
 - 属性extra @#1 用户需求1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('story')->loadYaml('story_processcreaterequirementsactionextra', false, 2)->gen(10);
zenData('user')->gen(5);

su('admin');

$actionTest = new actionTest();

r($actionTest->processCreateRequirementsActionExtraTest((object)array('extra' => '1'))) && p('extra') && e('#1 用户需求1');
r($actionTest->processCreateRequirementsActionExtraTest((object)array('extra' => '1,2,3'))) && p('extra') && e('#1 用户需求1, #2 软件需求2, #3 用户需求3');
r($actionTest->processCreateRequirementsActionExtraTest((object)array('extra' => '999'))) && p('extra') && e('0');
r($actionTest->processCreateRequirementsActionExtraTest((object)array('extra' => ''))) && p('extra') && e('0');
r($actionTest->processCreateRequirementsActionExtraTest((object)array('extra' => '1,999,2'))) && p('extra') && e('#1 用户需求1, #2 软件需求2');