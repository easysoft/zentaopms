#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';


/**

title=测试完成待办 todoModel->finish();
timeout=0
cid=19270

- 查询id=1的todo属性
 - 属性id @1
 - 属性status @wait
 - 属性account @admin
 - 属性type @custom
 - 属性name @自定义1的待办
- 查询id=2的todo属性
 - 属性id @2
 - 属性status @doing
 - 属性account @user1
 - 属性type @bug
 - 属性name @BUG2的待办
- 查询id=3的todo属性
 - 属性id @3
 - 属性status @done
 - 属性account @user2
 - 属性type @task
 - 属性name @任务3的待办
- 查询id=5的todo属性
 - 属性id @5
 - 属性status @wait
 - 属性account @user4
 - 属性type @testtask
 - 属性name @测试单5的待办
- 查询id=7的todo属性
 - 属性id @7
 - 属性status @done
 - 属性account @user6
 - 属性type @bug
 - 属性name @BUG7的待办
- 查询id=9的todo属性
 - 属性id @9
 - 属性status @wait
 - 属性account @user8
 - 属性type @story
 - 属性name @需求9的待办
- 查询id=10的todo属性
 - 属性id @10
 - 属性status @doing
 - 属性account @user9
 - 属性type @testtask
 - 属性name @测试单10的待办

*/

su('admin');
zenData('todo')->gen(10);

global $tester;
$tester->loadModel('todo')->todoTao;

r($tester->todo->fetch(1))  && p('id,status,account,type,name') && e('1,wait,admin,custom,自定义1的待办');      // 查询id=1的todo属性
r($tester->todo->fetch(2))  && p('id,status,account,type,name') && e('2,doing,user1,bug,BUG2的待办');           // 查询id=2的todo属性
r($tester->todo->fetch(3))  && p('id,status,account,type,name') && e('3,done,user2,task,任务3的待办');          // 查询id=3的todo属性
r($tester->todo->fetch(5))  && p('id,status,account,type,name') && e('5,wait,user4,testtask,测试单5的待办');    // 查询id=5的todo属性
r($tester->todo->fetch(7))  && p('id,status,account,type,name') && e('7,done,user6,bug,BUG7的待办');            // 查询id=7的todo属性
r($tester->todo->fetch(9))  && p('id,status,account,type,name') && e('9,wait,user8,story,需求9的待办');         // 查询id=9的todo属性
r($tester->todo->fetch(10)) && p('id,status,account,type,name') && e('10,doing,user9,testtask,测试单10的待办'); // 查询id=10的todo属性