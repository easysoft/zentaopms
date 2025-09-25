#!/usr/bin/env php
<?php

/**

title=测试 companyModel::getOutsideCompanies();
timeout=0
cid=0

- 步骤1：验证第一个外部公司存在属性2 @外部公司
- 步骤2：验证返回数组长度（5个公司-内部公司1个=4个外部公司） @4
- 步骤3：验证返回数组计数正确属性count(*) @4
- 步骤4：验证内部公司ID=1不在结果中属性1 @
- 步骤5：验证返回的键是2,3,4,5
 - 属性keys(*) @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/company.unittest.class.php';

// 使用基础的公司数据配置文件，生成多个公司数据进行测试
zendata('company')->loadYaml('company', false, 2)->gen(5);

su('admin');

$company = new companyTest();

r($company->getOutsideCompaniesTest()) && p('2') && e('外部公司'); // 步骤1：验证第一个外部公司存在
r($company->getOutsideCompaniesTest()) && p() && e('4'); // 步骤2：验证返回数组长度（5个公司-内部公司1个=4个外部公司）
r($company->getOutsideCompaniesTest()) && p('count(*)') && e('4'); // 步骤3：验证返回数组计数正确
r($company->getOutsideCompaniesTest()) && p('1') && e(''); // 步骤4：验证内部公司ID=1不在结果中
r($company->getOutsideCompaniesTest()) && p('keys(*)') && e('2,3,4,5'); // 步骤5：验证返回的键是2,3,4,5