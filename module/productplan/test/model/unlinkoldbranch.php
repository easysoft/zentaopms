#!/usr/bin/env php
<?php
/**

title=productpanModel->unlinkOldBranch();
timeout=0
cid=17649

- 分支没有变更
 - 第1条的id属性 @1
 - 第1条的title属性 @BUG1
 - 第1条的plan属性 @1
- 分支没有变更
 - 第3条的id属性 @3
 - 第3条的title属性 @BUG3
 - 第3条的plan属性 @1
- 分支没有变更
 - 第5条的id属性 @5
 - 第5条的title属性 @BUG5
 - 第5条的plan属性 @4
- 分支没有变更
 - 第9条的id属性 @9
 - 第9条的title属性 @BUG9
 - 第9条的plan属性 @7
- 分支有变更
 - 第3条的id属性 @3
 - 第3条的title属性 @BUG3
 - 第3条的plan属性 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$bug = zenData('bug');
$bug->branch->range('0-5');
$bug->gen(10);

$story = zenData('story');
$story->branch->range('0-5');
$story->plan->range('1,`2,3`,4,`5,6,7,8`,9,10');
$story->gen(10);

$tester = new productPlan();

r($tester->unlinkOldBranchTest(false)) && p('1:id,title,plan') && e('1,BUG1,1'); // 分支没有变更
r($tester->unlinkOldBranchTest(false)) && p('3:id,title,plan') && e('3,BUG3,1'); // 分支没有变更
r($tester->unlinkOldBranchTest(false)) && p('5:id,title,plan') && e('5,BUG5,4'); // 分支没有变更
r($tester->unlinkOldBranchTest(false)) && p('9:id,title,plan') && e('9,BUG9,7'); // 分支没有变更
r($tester->unlinkOldBranchTest(true))  && p('3:id,title,plan') && e('3,BUG3,0'); // 分支有变更
