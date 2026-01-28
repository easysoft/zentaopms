#!/usr/bin/env php
<?php

/**

title=测试 userModel->createCompany);
cid=19597

- 公司名称为空，返回 0。属性result @0
- 公司名称为空，提示错误信息。第errors条的newCompany属性 @『所属公司』不能为空。
- 公司名称不为空，返回创建的公司 id。属性result @2
- 数据库中查看刚创建的公司名称为 newCompany1。属性name @newCompany1
- 公司名称不为空，返回创建的公司 id。属性result @3
- 数据库中查看刚创建的公司名称为 newCompany2。属性name @newCompany2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(1);
zenData('company')->gen(1);

su('admin');

$userTest = new userModelTest();

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
