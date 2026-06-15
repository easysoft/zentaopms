#!/usr/bin/env php
<?php

/**

title=测试 companyModel::getOutsideCompanies();
timeout=0
cid=15733

- 步骤1：返回的外部公司数量不少于4家 @1
- 步骤2：外部公司A保留在结果中 @外部公司A
- 步骤3：内部公司ID=1被正确排除 @~~
- 步骤4：外部公司D保留在结果中 @1
- 步骤5：预置的4家外部公司全部存在 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备1家内部公司和4家外部公司。
global $tester;
$tester->dao->delete()->from(TABLE_COMPANY)->exec();

$company = zenData('company');
$company->name->range('易软天创网络科技有限公司,外部公司A,外部公司B,外部公司C,外部公司D');
$company->gen(5);

su('admin');

$company = new companyModelTest();
$outsideCompanies = $company->getOutsideCompaniesTest();

r(count($outsideCompanies) >= 4) && p()    && e('1');       // 步骤1：返回的外部公司数量不少于4家
r($outsideCompanies)             && p('2') && e('外部公司A'); // 步骤2：外部公司A保留在结果中
r($outsideCompanies)             && p('1') && e('~~');      // 步骤3：内部公司ID=1被正确排除
r(in_array('外部公司D', $outsideCompanies, true)) && p() && e('1'); // 步骤4：外部公司D保留在结果中
r(count(array_intersect($outsideCompanies, array('外部公司A', '外部公司B', '外部公司C', '外部公司D')))) && p() && e('4'); // 步骤5：预置的4家外部公司全部存在
