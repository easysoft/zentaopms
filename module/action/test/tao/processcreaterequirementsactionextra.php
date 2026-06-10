#!/usr/bin/env php
<?php

/**

title=- 执行actionTest模块的processCreateRequirementsActionExtraTest方法，参数是'1' 属性extra @<a href='/processcreaterequirementsactionextra.php?m=story&f=view&storyID=1'  >
timeout=0
cid=1

- 执行actionTest模块的processCreateRequirementsActionExtraTest方法，参数是'1' 属性extra @<a href='/processcreaterequirementsactionextra.php?m=story&f=view&storyID=1'  >#1 需求A</a>
- 执行actionTest模块的processCreateRequirementsActionExtraTest方法，参数是'1, 2, 3' 
 - 属性extra @<a href='/processcreaterequirementsactionextra.php?m=story&f=view&storyID=1'  >#1 需求A</a>
- 执行actionTest模块的processCreateRequirementsActionExtraTest方法，参数是'999' 属性extra @~~
- 执行actionTest模块的processCreateRequirementsActionExtraTest方法，参数是'' 属性extra @~~
- 执行actionTest模块的processCreateRequirementsActionExtraTest方法，参数是'1, 999, 2' 
 - 属性extra @<a href='/processcreaterequirementsactionextra.php?m=story&f=view&storyID=1'  >#1 需求A</a>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->title->range('需求A,需求B,需求C,需求D,需求E,需求F,需求G,需求H,需求I,需求J');
$storyTable->type->range('requirement');
$storyTable->product->range('1-3');
$storyTable->status->range('active{5},closed{3},draft{2}');
$storyTable->openedBy->range('admin,user1,user2,admin,user1,user2,admin,user1,user2,admin');
$storyTable->gen(10);

su('admin');

$actionTest = new actionTaoTest();

r(strpos($actionTest->processCreateRequirementsActionExtraTest('1')->extra, '#1 需求A') !== false) && p() && e('1');
r(strpos($actionTest->processCreateRequirementsActionExtraTest('1,2,3')->extra, '#1 需求A') !== false && strpos($actionTest->processCreateRequirementsActionExtraTest('1,2,3')->extra, '#2 需求B') !== false && strpos($actionTest->processCreateRequirementsActionExtraTest('1,2,3')->extra, '#3 需求C') !== false) && p() && e('1');
r($actionTest->processCreateRequirementsActionExtraTest('999')->extra == '') && p() && e('1');
r($actionTest->processCreateRequirementsActionExtraTest('')->extra == '') && p() && e('1');
r(strpos($actionTest->processCreateRequirementsActionExtraTest('1,999,2')->extra, '#1 需求A') !== false && strpos($actionTest->processCreateRequirementsActionExtraTest('1,999,2')->extra, '#2 需求B') !== false && strpos($actionTest->processCreateRequirementsActionExtraTest('1,999,2')->extra, '999') === false) && p() && e('1');