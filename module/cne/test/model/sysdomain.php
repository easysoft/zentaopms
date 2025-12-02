#!/usr/bin/env php
<?php

/**

title=测试 cneModel::sysDomain();
timeout=0
cid=15630

- 执行cneTest模块的sysDomainTest方法，参数是'empty_all'  @0
- 执行cneTest模块的sysDomainTest方法，参数是'config_only'  @config.test.com
- 执行cneTest模块的sysDomainTest方法，参数是'env_over_config'  @env.test.com
- 执行cneTest模块的sysDomainTest方法，参数是'db_only'  @db.test.com
- 执行cneTest模块的sysDomainTest方法，参数是'special_chars'  @sub-domain.test-env.com
- 执行cneTest模块的sysDomainTest方法，参数是'priority_test'  @db.test.com
- 执行cneTest模块的sysDomainTest方法，参数是'long_domain'  @aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.example.com

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 清理初始数据
zenData('config')->gen(0);

// 创建测试实例
$cneTest = new cneTest();

// 测试步骤1：所有域名配置都为空的情况
r($cneTest->sysDomainTest('empty_all')) && p() && e('0');

// 测试步骤2：仅配置文件中设置域名的情况
r($cneTest->sysDomainTest('config_only')) && p() && e('config.test.com');

// 测试步骤3：环境变量优先于配置文件的情况
r($cneTest->sysDomainTest('env_over_config')) && p() && e('env.test.com');

// 准备数据库配置数据
$config = zenData('config');
$config->owner->range('system');
$config->module->range('common');
$config->section->range('domain');
$config->key->range('customDomain');
$config->value->range('db.zentao.cc');
$config->gen(1);

// 测试步骤4：数据库配置优先级最高的情况
r($cneTest->sysDomainTest('db_only')) && p() && e('db.test.com');

// 测试步骤5：特殊字符域名处理的情况
r($cneTest->sysDomainTest('special_chars')) && p() && e('sub-domain.test-env.com');

// 测试步骤6：优先级综合测试的情况
r($cneTest->sysDomainTest('priority_test')) && p() && e('db.test.com');

// 测试步骤7：长域名处理的情况
r($cneTest->sysDomainTest('long_domain')) && p() && e('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.example.com');