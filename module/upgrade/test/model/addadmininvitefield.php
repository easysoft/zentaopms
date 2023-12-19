#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->hasConsistencyError();
cid=1

- 修改后，im_chart表中存在adminInvite字段   @adminInvite

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';
zdTable('user')->gen(1);
su('admin');

$upgrade = new upgradeTest();

$checkExistSql = "show columns from `%s` like '%s'";
$dropSql       = "Alter table `%s` drop column `%s`";

global $config, $tester;
$table = $config->db->prefix . 'im_chat';
$column = 'adminInvite';

$result = $tester->dbh->query(sprintf($checkExistSql, $table, $column))->fetch();
if($result) $deleteAction = $tester->dbh->exec(sprintf($dropSql, $table, $column)); //检测到adminInvite字段存在，删除该字段

$upgrade->addAdminInviteField();
$result2 = $tester->dbh->query(sprintf($checkExistSql, $table, $column))->fetch();

r($result2)  && p('Field') && e('adminInvite');  //修改后，im_chart表中存在adminInvite字段
