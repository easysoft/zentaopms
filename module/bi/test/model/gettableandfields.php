#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 biModel::getTableAndFields();
timeout=0
cid=15183

- 执行biModel模块的getTableAndFields方法，参数是'SELECT id, name FROM zt_user' 第tables条的0属性 @zt_user
- 执行biModel模块的getTableAndFields方法，参数是'SELECT id, name FROM zt_user' 第fields条的id属性 @id
- 执行biModel模块的getTableAndFields方法，参数是'SELECT u.id, p.name FROM zt_user u LEFT JOIN zt_project p ON u.id = p.createdBy')['tables']  @2
- 执行biModel模块的getTableAndFields方法，参数是'SELECT * FROM  第tables条的0属性 @zt_user
- 执行biModel模块的getTableAndFields方法，参数是'INVALID SQL STATEMENT'  @0

*/

global $tester;
$biModel = $tester->loadModel('bi');

r($biModel->getTableAndFields('SELECT id, name FROM zt_user')) && p('tables:0') && e('zt_user');
r($biModel->getTableAndFields('SELECT id, name FROM zt_user')) && p('fields:id') && e('id');
r(count($biModel->getTableAndFields('SELECT u.id, p.name FROM zt_user u LEFT JOIN zt_project p ON u.id = p.createdBy')['tables'])) && p() && e('2');
r($biModel->getTableAndFields('SELECT * FROM (SELECT id FROM zt_user) sub')) && p('tables:0') && e('zt_user');
r($biModel->getTableAndFields('INVALID SQL STATEMENT')) && p() && e('0');