#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::getByID();
timeout=0
cid=16649

- 测试步骤1：正常获取存在的GitLab配置属性id @1
- 测试步骤2：使用ID为0的边界值测试 @0
- 测试步骤3：使用负数ID测试无效输入 @0
- 测试步骤4：使用不存在的大数值ID测试 @0
- 测试步骤5：获取另一个存在的GitLab配置验证方法稳定性属性id @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('pipeline');
$table->id->range('1-5');
$table->name->range('gitlab1,gitlab2,gitlab3,gitlab4,gitlab5');
$table->type->range('gitlab{5}');
$table->url->range('http://gitlab1.test,http://gitlab2.test,http://gitlab3.test,http://gitlab4.test,http://gitlab5.test');
$table->token->range('token1,token2,token3,token4,token5');
$table->deleted->range('0{5}');
$table->gen(5);

su('admin');

$gitlab = new gitlabModelTest();

r($gitlab->getByIdTest(1)) && p('id') && e('1');          // 测试步骤1：正常获取存在的GitLab配置
r($gitlab->getByIdTest(0)) && p() && e('0');              // 测试步骤2：使用ID为0的边界值测试
r($gitlab->getByIdTest(-1)) && p() && e('0');             // 测试步骤3：使用负数ID测试无效输入
r($gitlab->getByIdTest(999)) && p() && e('0');            // 测试步骤4：使用不存在的大数值ID测试
r($gitlab->getByIdTest(2)) && p('id') && e('2');          // 测试步骤5：获取另一个存在的GitLab配置验证方法稳定性