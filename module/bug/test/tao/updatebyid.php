#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';
su('user1');

/**

title=bugTao->updateByID();
timeout=0
cid=1

- 更新bug title
 - 第0条的field属性 @title
 - 第0条的old属性 @BUG1
 - 第0条的new属性 @更新bug

- 更新bug branch
 - 第0条的field属性 @branch
 - 第0条的old属性 @0
 - 第0条的new属性 @1

- 更新bug module
 - 第0条的field属性 @module
 - 第0条的old属性 @1823
 - 第0条的new属性 @3

- 更新bug plan
 - 第0条的field属性 @plan
 - 第0条的old属性 @4
 - 第0条的new属性 @10

- 更新bug 后最后编辑人属性lastEditedBy @user1

*/

$bug = zdTable('bug')->gen(10);

$bugIDList = array(1, 2, 3, 4, 5, 6, 7);
$updateName = array('title' => '更新bug');
$updateBranch = array('branch' => 1);
$updateModule = array('module' => 3);
$updatePlan = array('plan' => 10);

$bug = new bugTest();
r($bug->updateByIDTest($bugIDList[0], $updateName)) && p('0:field,old,new') && e('title,BUG1,更新bug'); //更新bug title
r($bug->updateByIDTest($bugIDList[1], $updateBranch)) && p('0:field,old,new') && e('branch,0,1'); //更新bug branch
r($bug->updateByIDTest($bugIDList[2], $updateModule)) && p('0:field,old,new') && e('module,1823,3'); //更新bug module
r($bug->updateByIDTest($bugIDList[3], $updatePlan)) && p('0:field,old,new') && e('plan,4,10'); //更新bug plan
r($bug->updateByIDTest($bugIDList[5], $updatePlan, true)) && p('lastEditedBy') && e('user1'); //更新bug 后最后编辑人
