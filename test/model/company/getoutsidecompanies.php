#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/company.class.php';
su('admin');

/**

title=测试companyModel->getOutsideCompaniesTest();
cid=1
pid=1

查询外部公司 >> 外部公司

*/

$company = new companyTest();
r($company->getOutsideCompaniesTest()) && p('2')  && e('外部公司'); // 查询外部公司