#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/init.php';

/**

title=测试 userModel::getById();
cid=1
pid=1



*/
$user = $tester->loadModel('user');

$app->dbh->query("truncate zt_user");
zdImport(TABLE_USER, "zendata/user.yaml", 10);

$randUser = $tester->dao->select('*,rand() as rand')->from(TABLE_USER)->orderBy('rand')->fetch();
if(!$randUser) exit("Prepair data error.");
unset($randUser->rand);

/* Step 1.*/
run($user->getByID($randUser->id, 'id')) and expect('id,account');

/* Step 2.*/
run($user->getByID($randUser->account, 'account')) and expect('id');

/* Step 3.*/
run($user->getByID(null, 'id')) and expect('id');