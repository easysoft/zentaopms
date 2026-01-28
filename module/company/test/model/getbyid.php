#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('company')->gen(5);

/**

title=测试 companyModel::getByID();
timeout=0
cid=15731

- 步骤1：正常有效ID查询公司名称 @易软天创网络科技有限公司
- 步骤2：边界值ID=0的查询处理 @0
- 步骤3：负数ID的异常输入处理 @0
- 步骤4：超大ID值查询不存在公司 @0
- 步骤5：字符串类型ID的类型转换处理 @0

*/

$companyTest = new companyModelTest();

r($companyTest->getByIDTest(1)) && p('name') && e('易软天创网络科技有限公司');
r($companyTest->getByIDTest(0)) && p() && e('0');
r($companyTest->getByIDTest(-1)) && p() && e('0');
r($companyTest->getByIDTest(999)) && p() && e('0');
r($companyTest->getByIDTest('abc')) && p() && e('0');