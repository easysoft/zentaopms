#!/usr/bin/env php
<?php

/**

title=测试 companyModel::getFirst();
cid=15732

- 测试步骤1：正常情况下获取第一家公司 >> 期望返回第一家公司对象
- 测试步骤2：验证返回的公司对象包含完整字段 >> 期望包含id、name等字段
- 测试步骤3：验证返回的公司ID为1 >> 期望返回最小ID的公司
- 测试步骤4：验证返回的公司名称不为空 >> 期望name字段有值
- 测试步骤5：验证返回对象类型正确 >> 期望返回标准对象类型

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据，使用现有的company.yaml数据结构
zendata('company')->loadYaml('company', false, 2)->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$companyTest = new companyModelTest();

// 测试步骤1：正常情况下获取第一家公司
r($companyTest->getFirstTest()) && p('id,name') && e('1,易软天创网络科技有限公司');

// 测试步骤2：验证返回的公司对象包含完整字段
r($companyTest->getFirstTest()) && p('id,name,phone,website') && e('1,易软天创网络科技有限公司,15666933065,www.zentao.net');

// 测试步骤3：验证返回的公司ID为1（最小ID）
r($companyTest->getFirstTest()) && p('id') && e('1');

// 测试步骤4：验证返回的公司名称不为空
r($companyTest->getFirstTest()) && p('name') && e('易软天创网络科技有限公司');

// 测试步骤5：验证返回对象类型正确（检查是否为对象且包含关键属性）
r($companyTest->getFirstTest()) && p('admins,zipcode') && e(',admin,,100000');