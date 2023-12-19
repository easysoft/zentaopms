#!/usr/bin/env php
<?php
/**

title=测试 scoreModel->create();
cid=1

- 创建登录用户的积分
 - 属性account @admin
 - 属性module @user
 - 属性method @login
 - 属性score @1
- 创建修改密码的积分
 - 属性account @admin
 - 属性module @user
 - 属性method @changePassword
 - 属性score @10
- 创建修改密码的积分 @1
- 创建修改密码的积分 @1
- 创建关闭需求ID=0的积分 @1
- 创建关闭需求ID=1的积分
 - 属性account @admin
 - 属性module @story
 - 属性method @close
 - 属性score @2
- 创建关闭需求ID不存在的积分 @1
- 创建完成任务ID=0的积分 @1
- 创建完成任务ID=1的积分
 - 属性account @admin
 - 属性module @task
 - 属性method @finish
 - 属性score @3
- 创建完成父任务ID=6的积分 @1
- 创建完成任务ID不存在的积分 @1
- 创建用例ID=0的积分
 - 属性account @admin
 - 属性module @bug
 - 属性method @createFormCase
 - 属性score @1
- 创建用例ID=1的积分 @0
- 创建用例ID不存在的积分
 - 属性account @admin
 - 属性module @bug
 - 属性method @createFormCase
 - 属性score @1
- 创建保存模板bugID=0的积分
 - 属性account @admin
 - 属性module @bug
 - 属性method @saveTplModal
 - 属性score @20
- 创建保存模板bugID=1的积分 @1
- 创建保存模板bugID不存在的积分 @1
- 创建确认bugID=0的积分
 - 属性account @admin
 - 属性module @bug
 - 属性method @confirm
 - 属性score @4
- 创建确认bugID=1的积分
 - 属性account @admin
 - 属性module @bug
 - 属性method @confirm
 - 属性score @4
- 创建确认bugID不存在的积分
 - 属性account @admin
 - 属性module @bug
 - 属性method @confirm
 - 属性score @4
- 创建解决bugID=0的积分
 - 属性account @admin
 - 属性module @bug
 - 属性method @resolve
 - 属性score @4
- 创建解决bugID=1的积分
 - 属性account @admin
 - 属性module @bug
 - 属性method @resolve
 - 属性score @4
- 创建解决bugID不存在的积分
 - 属性account @admin
 - 属性module @bug
 - 属性method @resolve
 - 属性score @4
- 创建测试单执行用例的积分 @1
- 创建测试单执行用例的积分 @1
- 创建测试单执行用例的积分 @1
- 创建关闭执行ID=0的积分 @1
- 创建关闭执行ID=1的积分 @1
- 创建关闭执行ID不存在的积分 @1
- 创建保存搜索条件的积分
 - 属性account @admin
 - 属性module @search
 - 属性method @saveQueryAdvanced
 - 属性score @1
- 创建保存搜索条件的积分 @1
- 创建保存搜索条件的积分 @1
- 创建切换主题的积分
 - 属性account @admin
 - 属性module @ajax
 - 属性method @selectTheme
 - 属性score @10
- 创建切换主题的积分 @1
- 创建切换主题的积分 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/score.class.php';

zdTable('score')->gen(0);
zdTable('user')->gen(5);
zdTable('case')->gen(1);
zdTable('story')->gen(1);
zdTable('bug')->gen(1);
zdTable('task')->config('task')->gen(10);
zdTable('project')->config('project')->gen(5);

$modules = array('user', 'story', 'task', 'bug', 'testTask', 'execution', 'search', 'ajax');
$methods = array('login', 'changePassword', 'close', 'finish', 'createFormCase', 'saveTplModal', 'confirm', 'resolve', 'runCase', 'saveQueryAdvanced', 'selectTheme');
$params  = array(0, 1, 2, 6, 101, 110);

$scoreTester = new scoreTest();
r($scoreTester->createTest($modules[0], $methods[0], $params[0]))  && p('account,module,method,score') && e('admin,user,login,1');               // 创建登录用户的积分
r($scoreTester->createTest($modules[0], $methods[1], $params[0]))  && p('account,module,method,score') && e('admin,user,changePassword,10');     // 创建修改密码的积分
r($scoreTester->createTest($modules[0], $methods[1], $params[1]))  && p()                              && e('1');                                // 创建修改密码的积分
r($scoreTester->createTest($modules[0], $methods[1], $params[2]))  && p()                              && e('1');                                // 创建修改密码的积分
r($scoreTester->createTest($modules[1], $methods[2], $params[0]))  && p()                              && e('1');                                // 创建关闭需求ID=0的积分
r($scoreTester->createTest($modules[1], $methods[2], $params[1]))  && p('account,module,method,score') && e('admin,story,close,2');              // 创建关闭需求ID=1的积分
r($scoreTester->createTest($modules[1], $methods[2], $params[2]))  && p()                              && e('1');                                // 创建关闭需求ID不存在的积分
r($scoreTester->createTest($modules[2], $methods[3], $params[0]))  && p()                              && e('1');                                // 创建完成任务ID=0的积分
r($scoreTester->createTest($modules[2], $methods[3], $params[1]))  && p('account,module,method,score') && e('admin,task,finish,3');              // 创建完成任务ID=1的积分
r($scoreTester->createTest($modules[2], $methods[3], $params[3]))  && p()                              && e('1');                                // 创建完成父任务ID=6的积分
r($scoreTester->createTest($modules[2], $methods[3], $params[4]))  && p()                              && e('1');                                // 创建完成任务ID不存在的积分
r($scoreTester->createTest($modules[3], $methods[4], $params[0]))  && p('account,module,method,score') && e('admin,bug,createFormCase,1');       // 创建用例ID=0的积分
r($scoreTester->createTest($modules[3], $methods[4], $params[1]))  && p()                              && e('0');                                // 创建用例ID=1的积分
r($scoreTester->createTest($modules[3], $methods[4], $params[2]))  && p('account,module,method,score') && e('admin,bug,createFormCase,1');       // 创建用例ID不存在的积分
r($scoreTester->createTest($modules[3], $methods[5], $params[0]))  && p('account,module,method,score') && e('admin,bug,saveTplModal,20');        // 创建保存模板bugID=0的积分
r($scoreTester->createTest($modules[3], $methods[5], $params[1]))  && p()                              && e('1');                                // 创建保存模板bugID=1的积分
r($scoreTester->createTest($modules[3], $methods[5], $params[2]))  && p()                              && e('1');                                // 创建保存模板bugID不存在的积分
r($scoreTester->createTest($modules[3], $methods[6], $params[0]))  && p('account,module,method,score') && e('admin,bug,confirm,4');              // 创建确认bugID=0的积分
r($scoreTester->createTest($modules[3], $methods[6], $params[1]))  && p('account,module,method,score') && e('admin,bug,confirm,4');              // 创建确认bugID=1的积分
r($scoreTester->createTest($modules[3], $methods[6], $params[2]))  && p('account,module,method,score') && e('admin,bug,confirm,4');              // 创建确认bugID不存在的积分
r($scoreTester->createTest($modules[3], $methods[7], $params[0]))  && p('account,module,method,score') && e('admin,bug,resolve,4');              // 创建解决bugID=0的积分
r($scoreTester->createTest($modules[3], $methods[7], $params[1]))  && p('account,module,method,score') && e('admin,bug,resolve,4');              // 创建解决bugID=1的积分
r($scoreTester->createTest($modules[3], $methods[7], $params[2]))  && p('account,module,method,score') && e('admin,bug,resolve,4');              // 创建解决bugID不存在的积分
r($scoreTester->createTest($modules[4], $methods[8], $params[0]))  && p()                              && e('1');                                // 创建测试单执行用例的积分
r($scoreTester->createTest($modules[4], $methods[8], $params[1]))  && p()                              && e('1');                                // 创建测试单执行用例的积分
r($scoreTester->createTest($modules[4], $methods[8], $params[2]))  && p()                              && e('1');                                // 创建测试单执行用例的积分
r($scoreTester->createTest($modules[5], $methods[2], $params[0]))  && p()                              && e('1');                                // 创建关闭执行ID=0的积分
r($scoreTester->createTest($modules[5], $methods[2], $params[1]))  && p()                              && e('1');                                // 创建关闭执行ID=1的积分
r($scoreTester->createTest($modules[5], $methods[2], $params[2]))  && p()                              && e('1');                                // 创建关闭执行ID不存在的积分
r($scoreTester->createTest($modules[6], $methods[9], $params[0]))  && p('account,module,method,score') && e('admin,search,saveQueryAdvanced,1'); // 创建保存搜索条件的积分
r($scoreTester->createTest($modules[6], $methods[9], $params[1]))  && p()                              && e('1');                                // 创建保存搜索条件的积分
r($scoreTester->createTest($modules[6], $methods[9], $params[2]))  && p()                              && e('1');                                // 创建保存搜索条件的积分
r($scoreTester->createTest($modules[7], $methods[10], $params[0])) && p('account,module,method,score') && e('admin,ajax,selectTheme,10');        // 创建切换主题的积分
r($scoreTester->createTest($modules[7], $methods[10], $params[1])) && p()                              && e('1');                                // 创建切换主题的积分
r($scoreTester->createTest($modules[7], $methods[10], $params[2])) && p()                              && e('1');                                // 创建切换主题的积分
