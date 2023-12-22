#!/usr/bin/env php
<?php
/**
title=测试 userModel->createCompany);
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(1);
zdTable('company')->gen(1);

su('admin');

$userTest = new userTest();

$result = $userTest->createCompanyTest('');
r($result) && p('result')            && e(0);                        // 公司名称为空，返回 0。
r($result) && p('errors:newCompany') && e('『所属公司』不能为空。'); // 公司名称为空，提示错误信息。

$result = $userTest->createCompanyTest('newCompany1');
r($result) && p('result') && e(2);    // 公司名称不为空，返回创建的公司 id。

$company = $tester->dao->select('*')->from(TABLE_COMPANY)->where('id')->eq($result['result'])->fetch();
r($company) && p('name') && e('newCompany1'); // 数据库中查看刚创建的公司名称为 newCompany1。

$result = $userTest->createCompanyTest('newCompany2');
r($result) && p('result') && e(3);    // 公司名称不为空，返回创建的公司 id。

$company = $tester->dao->select('*')->from(TABLE_COMPANY)->where('id')->eq($result['result'])->fetch();
r($company) && p('name') && e('newCompany2'); // 数据库中查看刚创建的公司名称为 newCompany2。
