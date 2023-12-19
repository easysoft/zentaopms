#!/usr/bin/env php
<?php
/**

title=测试 scoreModel->saveScore();
cid=1

- 测试空数据 @1
- 保存admin用户登录的积分
 - 属性account @admin
 - 属性module @user
 - 属性method @login
 - 属性score @1
- 保存admin用户修改密码的积分
 - 属性account @admin
 - 属性module @user
 - 属性method @changePassword
 - 属性score @10
- 保存admin用户关闭需求的积分
 - 属性account @admin
 - 属性module @story
 - 属性method @close
 - 属性score @1
- 保存admin用户完成任务的积分
 - 属性account @admin
 - 属性module @task
 - 属性method @finish
 - 属性score @1
- 保存admin用户创建bug的积分
 - 属性account @admin
 - 属性module @bug
 - 属性method @createFormCase
 - 属性score @1
- 保存admin用户保存模版的积分
 - 属性account @admin
 - 属性module @bug
 - 属性method @saveTplModal
 - 属性score @20
- 保存admin用户确认bug的积分
 - 属性account @admin
 - 属性module @bug
 - 属性method @confirm
 - 属性score @1
- 保存admin用户解决bug的积分
 - 属性account @admin
 - 属性module @bug
 - 属性method @resolve
 - 属性score @1
- 保存admin用户执行测试用例的积分 @1
- 保存admin用户关闭执行的积分 @1
- 保存admin用户保存高级搜索的积分
 - 属性account @admin
 - 属性module @search
 - 属性method @saveQueryAdvanced
 - 属性score @1
- 保存admin用户选择主题的积分
 - 属性account @admin
 - 属性module @ajax
 - 属性method @selectTheme
 - 属性score @10
- 测试空数据 @1
- 保存user1用户登录的积分
 - 属性account @user1
 - 属性module @user
 - 属性method @login
 - 属性score @1
- 保存user1用户修改密码的积分
 - 属性account @user1
 - 属性module @user
 - 属性method @changePassword
 - 属性score @10
- 保存user1用户关闭需求的积分
 - 属性account @user1
 - 属性module @story
 - 属性method @close
 - 属性score @1
- 保存user1用户完成任务的积分
 - 属性account @user1
 - 属性module @task
 - 属性method @finish
 - 属性score @1
- 保存user1用户创建bug的积分
 - 属性account @user1
 - 属性module @bug
 - 属性method @createFormCase
 - 属性score @1
- 保存user1用户保存模版的积分
 - 属性account @user1
 - 属性module @bug
 - 属性method @saveTplModal
 - 属性score @20
- 保存user1用户确认bug的积分
 - 属性account @user1
 - 属性module @bug
 - 属性method @confirm
 - 属性score @1
- 保存user1用户解决bug的积分
 - 属性account @user1
 - 属性module @bug
 - 属性method @resolve
 - 属性score @1
- 保存user1用户执行测试用例的积分 @1
- 保存user1用户关闭执行的积分 @1
- 保存user1用户保存高级搜索的积分
 - 属性account @user1
 - 属性module @search
 - 属性method @saveQueryAdvanced
 - 属性score @1
- 保存user1用户选择主题的积分
 - 属性account @user1
 - 属性module @ajax
 - 属性method @selectTheme
 - 属性score @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/score.class.php';

zdTable('score')->gen(0);
zdTable('user')->gen(5);

$accounts = array('admin', 'user1');
$modules  = array('', 'user', 'story', 'task', 'bug', 'testTask', 'execution', 'search', 'ajax');
$methods  = array('', 'login', 'changePassword', 'close', 'finish', 'createFormCase', 'saveTplModal', 'confirm', 'resolve', 'runCase', 'saveQueryAdvanced', 'selectTheme');

$scoreTester = new scoreTest();

/* Admin user. */
r($scoreTester->saveScoreTest($accounts[0], $modules[0], $methods[0]))  && p()                              && e('1');                                // 测试空数据
r($scoreTester->saveScoreTest($accounts[0], $modules[1], $methods[1]))  && p('account,module,method,score') && e('admin,user,login,1');               // 保存admin用户登录的积分
r($scoreTester->saveScoreTest($accounts[0], $modules[1], $methods[2]))  && p('account,module,method,score') && e('admin,user,changePassword,10');     // 保存admin用户修改密码的积分
r($scoreTester->saveScoreTest($accounts[0], $modules[2], $methods[3]))  && p('account,module,method,score') && e('admin,story,close,1');              // 保存admin用户关闭需求的积分
r($scoreTester->saveScoreTest($accounts[0], $modules[3], $methods[4]))  && p('account,module,method,score') && e('admin,task,finish,1');              // 保存admin用户完成任务的积分
r($scoreTester->saveScoreTest($accounts[0], $modules[4], $methods[5]))  && p('account,module,method,score') && e('admin,bug,createFormCase,1');       // 保存admin用户创建bug的积分
r($scoreTester->saveScoreTest($accounts[0], $modules[4], $methods[6]))  && p('account,module,method,score') && e('admin,bug,saveTplModal,20');        // 保存admin用户保存模版的积分
r($scoreTester->saveScoreTest($accounts[0], $modules[4], $methods[7]))  && p('account,module,method,score') && e('admin,bug,confirm,1');              // 保存admin用户确认bug的积分
r($scoreTester->saveScoreTest($accounts[0], $modules[4], $methods[8]))  && p('account,module,method,score') && e('admin,bug,resolve,1');              // 保存admin用户解决bug的积分
r($scoreTester->saveScoreTest($accounts[0], $modules[5], $methods[9]))  && p()                              && e('1');                                // 保存admin用户执行测试用例的积分
r($scoreTester->saveScoreTest($accounts[0], $modules[6], $methods[3]))  && p()                              && e('1');                                // 保存admin用户关闭执行的积分
r($scoreTester->saveScoreTest($accounts[0], $modules[7], $methods[10])) && p('account,module,method,score') && e('admin,search,saveQueryAdvanced,1'); // 保存admin用户保存高级搜索的积分
r($scoreTester->saveScoreTest($accounts[0], $modules[8], $methods[11])) && p('account,module,method,score') && e('admin,ajax,selectTheme,10');        // 保存admin用户选择主题的积分

/* user1 user. */
r($scoreTester->saveScoreTest($accounts[1], $modules[0], $methods[0]))  && p()                              && e('1');                                // 测试空数据
r($scoreTester->saveScoreTest($accounts[1], $modules[1], $methods[1]))  && p('account,module,method,score') && e('user1,user,login,1');               // 保存user1用户登录的积分
r($scoreTester->saveScoreTest($accounts[1], $modules[1], $methods[2]))  && p('account,module,method,score') && e('user1,user,changePassword,10');     // 保存user1用户修改密码的积分
r($scoreTester->saveScoreTest($accounts[1], $modules[2], $methods[3]))  && p('account,module,method,score') && e('user1,story,close,1');              // 保存user1用户关闭需求的积分
r($scoreTester->saveScoreTest($accounts[1], $modules[3], $methods[4]))  && p('account,module,method,score') && e('user1,task,finish,1');              // 保存user1用户完成任务的积分
r($scoreTester->saveScoreTest($accounts[1], $modules[4], $methods[5]))  && p('account,module,method,score') && e('user1,bug,createFormCase,1');       // 保存user1用户创建bug的积分
r($scoreTester->saveScoreTest($accounts[1], $modules[4], $methods[6]))  && p('account,module,method,score') && e('user1,bug,saveTplModal,20');        // 保存user1用户保存模版的积分
r($scoreTester->saveScoreTest($accounts[1], $modules[4], $methods[7]))  && p('account,module,method,score') && e('user1,bug,confirm,1');              // 保存user1用户确认bug的积分
r($scoreTester->saveScoreTest($accounts[1], $modules[4], $methods[8]))  && p('account,module,method,score') && e('user1,bug,resolve,1');              // 保存user1用户解决bug的积分
r($scoreTester->saveScoreTest($accounts[1], $modules[5], $methods[9]))  && p()                              && e('1');                                // 保存user1用户执行测试用例的积分
r($scoreTester->saveScoreTest($accounts[1], $modules[6], $methods[3]))  && p()                              && e('1');                                // 保存user1用户关闭执行的积分
r($scoreTester->saveScoreTest($accounts[1], $modules[7], $methods[10])) && p('account,module,method,score') && e('user1,search,saveQueryAdvanced,1'); // 保存user1用户保存高级搜索的积分
r($scoreTester->saveScoreTest($accounts[1], $modules[8], $methods[11])) && p('account,module,method,score') && e('user1,ajax,selectTheme,10');        // 保存user1用户选择主题的积分
