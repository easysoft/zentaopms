#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processStoryGradeActionExtra();
timeout=0
cid=14967

- 执行actionTest模块的processStoryGradeActionExtraTest方法，参数是1 属性extra @UR
- 执行actionTest模块的processStoryGradeActionExtraTest方法，参数是2 属性extra @SR
- 执行actionTest模块的processStoryGradeActionExtraTest方法，参数是3 属性extra @子
- 执行actionTest模块的processStoryGradeActionExtraTest方法，参数是4 属性extra @BR
- 执行actionTest模块的processStoryGradeActionExtraTest方法，参数是999 属性extra @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$storyTable = zenData('story');
$storyTable->id->range('1-5');
$storyTable->type->range('requirement,story,story,epic,story');
$storyTable->grade->range('1,1,2,1,1');
$storyTable->gen(5);

$storyGradeTable = zenData('storygrade');
$storyGradeTable->type->range('requirement,epic,story,story');
$storyGradeTable->grade->range('1,1,1,2');
$storyGradeTable->name->range('UR,BR,SR,子');
$storyGradeTable->status->range('enable{4}');
$storyGradeTable->gen(4);

su('admin');

$actionTest = new actionTaoTest();

r($actionTest->processStoryGradeActionExtraTest(1)) && p('extra') && e('UR');
r($actionTest->processStoryGradeActionExtraTest(2)) && p('extra') && e('SR'); 
r($actionTest->processStoryGradeActionExtraTest(3)) && p('extra') && e('子');
r($actionTest->processStoryGradeActionExtraTest(4)) && p('extra') && e('BR');
r($actionTest->processStoryGradeActionExtraTest(999)) && p('extra') && e('~~');