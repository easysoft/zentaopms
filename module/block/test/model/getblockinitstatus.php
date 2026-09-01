#!/usr/bin/env php
<?php

/**

title=测试 blockModel::getBlockInitStatus();
timeout=0
cid=15228

- 执行blockTest模块的getBlockInitStatusTest方法，参数是'my'  @1
- 执行blockTest模块的getBlockInitStatusTest方法，参数是'product'  @0
- 执行blockTest模块的getBlockInitStatusTest方法，参数是''  @0
- 执行blockTest模块的getBlockInitStatusTest方法，参数是'nonexistent_dashboard'  @0
- 执行blockTest模块的getBlockInitStatusTest方法，参数是'project'  @0
- 执行blockTest模块的getBlockInitStatusTest方法，参数是'my'  @1
- 执行blockTest模块的getBlockInitStatusTest方法，参数是'my'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$zd_company = zenData('company');
$zd_company->id->range('1');
$zd_company->name->range('禅道软件');
$zd_company->admins->range('admin');
$zd_company->guest->range('0');
$zd_company->gen(1);
$dbh->exec("UPDATE zt_company SET admins = ',admin,' WHERE id = 1");
global $app;
$app->company = $dbh->query('SELECT * FROM zt_company WHERE id = 1')->fetch(PDO::FETCH_OBJ);

$zd_user = zenData('user');
$zd_user->id->range('1-3');
$zd_user->account->range('admin,user1,user2');
$zd_user->realname->range('管理员,用户1,用户2');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(3);

su('admin');

global $tester;
$tester->loadModel('setting')->setItem("admin.my.common.blockInited@rnd", '1');
$tester->loadModel('setting')->setItem("user1.my.common.blockInited@rnd", '1');

$blockTest = new blockModelTest();

r($blockTest->getBlockInitStatusTest('my')) && p('') && e('1');
r($blockTest->getBlockInitStatusTest('product')) && p('') && e('0');
r($blockTest->getBlockInitStatusTest('')) && p('') && e('0');
r($blockTest->getBlockInitStatusTest('nonexistent_dashboard')) && p('') && e('0');
r($blockTest->getBlockInitStatusTest('project')) && p('') && e('0');

su('user1');
r($blockTest->getBlockInitStatusTest('my')) && p('') && e('1');

su('user2');
r($blockTest->getBlockInitStatusTest('my')) && p('') && e('0');
