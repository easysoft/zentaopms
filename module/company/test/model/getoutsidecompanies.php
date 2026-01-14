#!/usr/bin/env php
<?php

/**

title=测试 companyModel::getOutsideCompanies();
timeout=0
cid=15733

- 步骤1：测试获取外部公司列表返回数组长度为4 @4
- 步骤2：验证第一个外部公司名称为外部公司A属性2 @外部公司A
- 步骤3：验证内部公司ID=1被正确排除属性1 @0
- 步骤4：验证最后一个外部公司名称为外部公司D属性5 @外部公司D
- 步骤5：验证返回的键值结构正确
 - 属性keys(*) @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 确保有基本的公司数据
zendata('company')->gen(1);
zendata('company')->loadYaml('company_getoutsidecompanies', false, 2)->gen(5);

su('admin');

$company = new companyModelTest();

r($company->getOutsideCompaniesTest()) && p() && e('4'); // 步骤1：测试获取外部公司列表返回数组长度为4
r($company->getOutsideCompaniesTest()) && p('2') && e('外部公司A'); // 步骤2：验证第一个外部公司名称为外部公司A
r($company->getOutsideCompaniesTest()) && p('1') && e('0'); // 步骤3：验证内部公司ID=1被正确排除
r($company->getOutsideCompaniesTest()) && p('5') && e('外部公司D'); // 步骤4：验证最后一个外部公司名称为外部公司D
r($company->getOutsideCompaniesTest()) && p('keys(*)') && e('2,3,4,5'); // 步骤5：验证返回的键值结构正确