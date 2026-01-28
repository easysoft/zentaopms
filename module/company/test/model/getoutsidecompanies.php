#!/usr/bin/env php
<?php

/**

title=测试 companyModel::getOutsideCompanies();
timeout=0
cid=15733

- 步骤1：测试获取外部公司列表返回数组长度为4 @4
- 步骤2：验证第一个外部公司名称为外部公司A属性2 @外部公司A
- 步骤3：验证内部公司ID=1被正确排除属性1 @~~
- 步骤4：验证最后一个外部公司名称为外部公司D属性5 @外部公司D
- 步骤5：验证返回的键值结构正确
 -  @2
 - 属性1 @3
 - 属性2 @4
 - 属性3 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 确保有基本的公司数据
zendata('company')->gen(1);
zendata('company')->loadYaml('company_getoutsidecompanies', false, 2)->gen(5);

su('admin');

$company = new companyModelTest();

r(count($company->getOutsideCompaniesTest()))      && p()          && e('4');         // 步骤1：测试获取外部公司列表返回数组长度为4
r($company->getOutsideCompaniesTest())             && p('2')       && e('外部公司A'); // 步骤2：验证第一个外部公司名称为外部公司A
r($company->getOutsideCompaniesTest())             && p('1')       && e('~~');        // 步骤3：验证内部公司ID=1被正确排除
r($company->getOutsideCompaniesTest())             && p('5')       && e('外部公司D'); // 步骤4：验证最后一个外部公司名称为外部公司D
r(array_keys($company->getOutsideCompaniesTest())) && p('0,1,2,3') && e('2,3,4,5');   // 步骤5：验证返回的键值结构正确
