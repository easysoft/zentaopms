#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->update();
cid=1
pid=1

测试修改标题 >> title,这个是测试用例1,修改后的名称
测试重复修改标题 >> 没有数据更新
测试修改前置条件 >> precondition,这是前置条件1,修改后的前置条件
测试修改状态 >> status,wait,normal
测试修改优先级 >> pri,1,3

*/

$changeTitle        = array('title' => '修改后的名称');
$changePrecondition = array('precondition' => '修改后的前置条件');
$changeStatus       = array('status' => 'normal');
$changePri          = array('pri' => '3');

$testcase = new testcaseTest();

r($testcase->updateTest($changeTitle))        && p('0:field,old,new') && e('title,这个是测试用例1,修改后的名称');          // 测试修改标题
r($testcase->updateTest($changeTitle))        && p('0:field,old,new') && e('没有数据更新');                                // 测试重复修改标题
r($testcase->updateTest($changePrecondition)) && p('0:field,old,new') && e('precondition,这是前置条件1,修改后的前置条件'); // 测试修改前置条件
r($testcase->updateTest($changeStatus))       && p('0:field,old,new') && e('status,wait,normal');                          // 测试修改状态
r($testcase->updateTest($changePri))          && p('0:field,old,new') && e('pri,1,3');                                     // 测试修改优先级
