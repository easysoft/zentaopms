#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/company.class.php';
su('admin');

zdTable('company')->gen(2);

/**

title=测试companyModel->getOutsideCompaniesTest();
cid=1
pid=1

查询外部公司 >> 外部公司

*/

$company = new companyTest();
r($company->getOutsideCompaniesTest()) && p('2')  && e('外部公司'); // 查询外部公司
