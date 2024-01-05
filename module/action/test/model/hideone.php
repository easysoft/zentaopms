#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->config('action')->gen(2);

/**

title=测试 actionModel->hideOne();
timeout=0
cid=1

- 隐藏action1属性extra @2
- 隐藏action2属性extra @1
- 检查是否有action1的删除数据属性objectID @1
- 检查是否有action2的删除数据 @0

*/
$actionIDList                 = array(1, 2);
$type                         = array('product', 'story');
$deleteAction1SuccessActionID = 3;
$deleteAction2SuccessActionID = 4;

$action = new actionTest();

r($action->hideOneTest($actionIDList[0]))              && p('extra')    && e('2'); //隐藏action1
r($action->hideOneTest($actionIDList[1]))              && p('extra')    && e('1'); //隐藏action2
r($action->getByIdTest($deleteAction1SuccessActionID)) && p('objectID') && e('1'); //检查是否有action1的删除数据
r($action->getByIdTest($deleteAction2SuccessActionID)) && p('')         && e('0'); //检查是否有action2的删除数据