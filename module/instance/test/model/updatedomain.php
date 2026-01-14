#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::updateDomain();
timeout=0
cid=16821

- 执行instanceTest模块的updateDomainTest方法，参数是$instance1  @1
- 执行instanceTest模块的updateDomainTest方法，参数是$instance2  @1
- 执行instanceTest模块的updateDomainTest方法，参数是$instance3  @1
- 执行instanceTest模块的updateDomainTest方法，参数是$instance4  @1
- 执行instanceTest模块的updateDomainTest方法，参数是$instance5  @1
- 执行instanceTest模块的updateDomainTest方法，参数是$instance6  @1
- 执行instanceTest模块的updateDomainTest方法，参数是$emptyInstance  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
zendata('instance')->loadYaml('instance_updatedomain', false, 2)->gen(5);
zendata('space')->loadYaml('space_updatedomain', false, 2)->gen(3);

// 准备配置数据，模拟不同的配置场景
$configData = zenData('config');
$configData->owner->range('system');
$configData->module->range('common');
$configData->section->range('domain');
$configData->key->range('expiredDomain,customDomain,sysDomain');
$configData->value->range('`["old.domain.com","expired.com"]`,current.com,new.zentao.com');
$configData->gen(3);

// 登录管理员
su('admin');

// 创建测试实例
$instanceTest = new instanceModelTest();

// 测试步骤1：域名与系统域名相同的情况
$instance1 = new stdClass();
$instance1->id = 1;
$instance1->domain = 'new.zentao.com';
$instance1->space = 1;
$instance1->k8name = 'test-app-1';
$instance1->chart = 'zentao';
$instance1->spaceData = new stdClass();
$instance1->spaceData->k8space = 'default';
r($instanceTest->updateDomainTest($instance1)) && p() && e('1');

// 测试步骤2：没有过期域名配置的情况
// 清空配置中的expiredDomain
global $tester;
$tester->dao->update(TABLE_CONFIG)->set('value')->eq('[]')->where('key')->eq('expiredDomain')->exec();
$instance2 = new stdClass();
$instance2->id = 2;
$instance2->domain = 'test.old.domain.com';
$instance2->space = 1;
$instance2->k8name = 'test-app-2';
$instance2->chart = 'zentao';
$instance2->spaceData = new stdClass();
$instance2->spaceData->k8space = 'test';
r($instanceTest->updateDomainTest($instance2)) && p() && e('1');

// 重新设置过期域名配置
$tester->dao->update(TABLE_CONFIG)->set('value')->eq('["old.domain.com","expired.com"]')->where('key')->eq('expiredDomain')->exec();

// 测试步骤3：域名不匹配过期域名的情况
$instance3 = new stdClass();
$instance3->id = 3;
$instance3->domain = 'valid.new.com';
$instance3->space = 1;
$instance3->k8name = 'test-app-3';
$instance3->chart = 'zentao';
$instance3->spaceData = new stdClass();
$instance3->spaceData->k8space = 'default';
r($instanceTest->updateDomainTest($instance3)) && p() && e('1');

// 测试步骤4：域名匹配过期域名但包含点号的情况
$instance4 = new stdClass();
$instance4->id = 4;
$instance4->domain = 'sub.test.old.domain.com';
$instance4->space = 1;
$instance4->k8name = 'test-app-4';
$instance4->chart = 'zentao';
$instance4->spaceData = new stdClass();
$instance4->spaceData->k8space = 'default';
r($instanceTest->updateDomainTest($instance4)) && p() && e('1');

// 测试步骤5：域名匹配过期域名且符合更新条件的情况
$instance5 = new stdClass();
$instance5->id = 5;
$instance5->domain = 'simple.expired.com';
$instance5->space = 1;
$instance5->k8name = 'test-app-5';
$instance5->chart = 'zentao';
$instance5->spaceData = new stdClass();
$instance5->spaceData->k8space = 'default';
r($instanceTest->updateDomainTest($instance5)) && p() && e('1');

// 测试步骤6：测试域名替换逻辑
$instance6 = new stdClass();
$instance6->id = 6;
$instance6->domain = 'test.old.domain.com';
$instance6->space = 1;
$instance6->k8name = 'test-app-6';
$instance6->chart = 'zentao';
$instance6->spaceData = new stdClass();
$instance6->spaceData->k8space = 'default';
r($instanceTest->updateDomainTest($instance6)) && p() && e('1');

// 测试步骤7：测试空对象情况
$emptyInstance = new stdClass();
$emptyInstance->domain = '';
$emptyInstance->space = 1;
$emptyInstance->k8name = 'empty-instance';
$emptyInstance->chart = 'zentao';
$emptyInstance->spaceData = new stdClass();
$emptyInstance->spaceData->k8space = 'default';
r($instanceTest->updateDomainTest($emptyInstance)) && p() && e('1');